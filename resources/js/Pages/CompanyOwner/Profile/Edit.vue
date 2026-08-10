<template>
    <CompanyOwnerDashboardLayout>
        <section class="tw-py-8 tw-mx-auto lg:tw-px-12">
            <div class="tw-grid tw-grid-cols-1 xl:tw-grid-cols-3 tw-gap-8">
                <!-- FORM -->
                <div class="xl:tw-col-span-2">
                    <div
                        class="tw-rounded-3xl tw-bg-white tw-shadow-sm tw-ring-1 ring-black/5"
                    >
                        <div class="tw-p-6 sm:tw-p-8">
                            <h1
                                class="tw-text-2xl tw-font-bold tw-text-slate-900"
                            >
                                Mini-site settings
                            </h1>
                            <p class="tw-mt-1 tw-text-slate-600">
                                Completează datele care apar pe pagina publică.
                            </p>

                            <!-- ERRORS -->
                            <div
                                v-if="hasAnyError"
                                class="tw-mt-4 tw-rounded-xl tw-border tw-border-rose-200 tw-bg-rose-50 tw-p-4"
                            >
                                <ul
                                    class="tw-text-sm tw-text-rose-700 tw-space-y-1"
                                >
                                    <li
                                        v-for="(msg, key) in flatErrors"
                                        :key="key"
                                    >
                                        • {{ msg }}
                                    </li>
                                </ul>
                            </div>

                            <!-- BASIC -->
                            <div
                                class="tw-mt-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5"
                            >
                                <div>
                                    <label
                                        class="tw-block tw-text-sm tw-font-medium tw-text-slate-700"
                                        >Denumire firmă</label
                                    >
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        class="tw-text-black focus:tw-text-black tw-mt-2 tw-w-full tw-rounded-xl tw-border-slate-300 focus:tw-border-[var(--theme)] focus:tw-ring-[var(--theme)]"
                                        placeholder="Ex: Renteaza SRL"
                                    />
                                    <FormError :message="errors.name" />
                                </div>

                                <div>
                                    <label
                                        class="tw-block tw-text-sm tw-font-medium tw-text-slate-700"
                                        >Website</label
                                    >
                                    <input
                                        v-model="form.website"
                                        type="text"
                                        class="tw-text-black focus:tw-text-black tw-mt-2 tw-w-full tw-rounded-xl tw-border-slate-300 focus:tw-border-[var(--theme)] focus:tw-ring-[var(--theme)]"
                                        placeholder="https://exemplu.ro"
                                    />
                                    <FormError :message="errors.website" />
                                </div>

                                <div>
                                    <label
                                        class="tw-block tw-text-sm tw-font-medium tw-text-slate-700"
                                        >Email</label
                                    >
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        class="tw-text-black focus:tw-text-black tw-mt-2 tw-w-full tw-rounded-xl tw-border-slate-300 focus:tw-border-[var(--theme)] focus:tw-ring-[var(--theme)]"
                                        placeholder="contact@firma.ro"
                                    />
                                    <FormError :message="errors.email" />
                                </div>

                                <div>
                                    <label
                                        class="tw-block tw-text-sm tw-font-medium tw-text-slate-700"
                                        >Telefon</label
                                    >
                                    <input
                                        v-model="form.phone"
                                        type="text"
                                        class="tw-text-black focus:tw-text-black tw-mt-2 tw-w-full tw-rounded-xl tw-border-slate-300 focus:tw-border-[var(--theme)] focus:tw-ring-[var(--theme)]"
                                        placeholder="+40 7xx xxx xxx"
                                    />
                                    <FormError :message="errors.phone" />
                                </div>

                                <div class="sm:tw-col-span-2">
                                    <label
                                        class="tw-block tw-text-sm tw-font-medium tw-text-slate-700"
                                        >Descriere</label
                                    >
                                    <textarea
                                        v-model="form.description"
                                        rows="4"
                                        class="tw-text-black focus:tw-text-black tw-mt-2 tw-w-full tw-rounded-xl tw-border-slate-300 focus:tw-border-[var(--theme)] focus:tw-ring-[var(--theme)]"
                                        placeholder="Scurtă prezentare..."
                                    ></textarea>

                                    <FormError :message="errors.description" />
                                </div>
                            </div>

                            <!-- ADRESĂ & COORDS -->
                            <div
                                class="tw-mt-6 tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-5"
                            >
                                <div class="sm:tw-col-span-2">
                                    <label
                                        class="tw-block tw-text-sm tw-font-medium tw-text-slate-700"
                                        >Adresă</label
                                    >
                                    <input
                                        v-model="form.address"
                                        type="text"
                                        class="tw-mt-2 focus:tw-text-black tw-text-black tw-w-full tw-rounded-xl tw-border-slate-300 focus:tw-border-[var(--theme)] focus:tw-ring-[var(--theme)]"
                                        placeholder="Str. Exemplu 10, București"
                                    />
                                    <FormError :message="errors.address" />
                                </div>
                            </div>

                            <!-- LOGO -->
                            <div class="tw-mt-8">
                                <label
                                    class="tw-block tw-text-sm tw-font-semibold tw-text-slate-900"
                                    >Logo</label
                                >
                                <div
                                    class="tw-mt-3 tw-flex tw-items-center tw-gap-4"
                                >
                                    <div
                                        class="tw-h-20 tw-w-20 tw-rounded-2xl tw-overflow-hidden tw-ring-1 ring-black/5 tw-flex tw-items-center tw-justify-center tw-bg-slate-50"
                                    >
                                        <img
                                            v-if="preview.logo"
                                            :src="toPublicUrl(preview.logo)"
                                            class="tw-h-full tw-w-full tw-object-contain"
                                            alt="logo preview"
                                        />
                                        <span
                                            v-else
                                            class="tw-text-slate-400 tw-text-sm"
                                            >fără</span
                                        >
                                    </div>
                                    <div
                                        class="tw-flex tw-items-center tw-gap-2"
                                    >
                                        <input
                                            ref="logoInput"
                                            type="file"
                                            accept="image/*"
                                            class="tw-hidden"
                                            @change="onPickLogo"
                                        />
                                        <button
                                            type="button"
                                            class="tw-rounded-xl tw-bg-white tw-border tw-border-slate-200 tw-px-4 tw-py-2 tw-text-sm tw-font-medium hover:tw-bg-slate-50"
                                            @click="logoInput?.click()"
                                        >
                                            Încarcă
                                        </button>
                                        <button
                                            v-if="preview.logo"
                                            type="button"
                                            class="tw-rounded-xl tw-bg-rose-50 tw-text-rose-700 tw-border tw-border-rose-200 tw-px-3 tw-py-2 tw-text-sm hover:tw-bg-rose-100"
                                            @click="removeLogo"
                                        >
                                            Șterge
                                        </button>
                                    </div>
                                </div>
                                <FormError :message="errors.logo" />
                            </div>

                            <!-- GALERIE -->
                            <div class="tw-mt-10">
                                <div
                                    class="tw-flex tw-items-center tw-justify-between"
                                >
                                    <label
                                        class="tw-text-sm tw-font-semibold tw-text-slate-900"
                                        >Galerie foto</label
                                    >
                                    <div class="tw-space-x-2">
                                        <input
                                            ref="galleryInput"
                                            type="file"
                                            multiple
                                            accept="image/*"
                                            class="tw-hidden"
                                            @change="onPickGallery"
                                        />
                                        <button
                                            type="button"
                                            class="tw-rounded-xl tw-bg-white tw-border tw-border-slate-200 tw-px-4 tw-py-2 tw-text-sm hover:tw-bg-slate-50"
                                            @click="galleryInput?.click()"
                                        >
                                            Adaugă imagini
                                        </button>
                                    </div>
                                </div>

                                <div
                                    v-if="preview.images.length"
                                    class="tw-mt-4 tw-grid tw-grid-cols-2 sm:tw-grid-cols-3 lg:tw-grid-cols-4 tw-gap-4"
                                >
                                    <div
                                        v-for="(img, idx) in preview.images"
                                        :key="img.key"
                                        class="tw-group tw-relative tw-rounded-xl tw-overflow-hidden tw-ring-1 ring-black/5 tw-bg-slate-50"
                                    >
                                        <img
                                            :src="toPublicUrl(img.url)"
                                            class="tw-aspect-[4/3] tw-w-full tw-object-cover"
                                            :alt="`img ${idx + 1}`"
                                        />
                                        <div
                                            class="tw-absolute tw-inset-x-0 tw-bottom-0 tw-flex tw-justify-between tw-gap-2 tw-p-2 tw-bg-gradient-to-t tw-from-black/60 tw-to-transparent"
                                        >
                                            <div class="tw-flex tw-gap-1">
                                                <button
                                                    type="button"
                                                    class="tw-rounded tw-bg-white/90 tw-px-2 tw-py-1 tw-text-xs hover:tw-bg-white"
                                                    @click="move(idx, -1)"
                                                    :disabled="idx === 0"
                                                >
                                                    ↑
                                                </button>
                                                <button
                                                    type="button"
                                                    class="tw-rounded tw-bg-white/90 tw-px-2 tw-py-1 tw-text-xs hover:tw-bg-white"
                                                    @click="move(idx, +1)"
                                                    :disabled="
                                                        idx ===
                                                        preview.images.length -
                                                            1
                                                    "
                                                >
                                                    ↓
                                                </button>
                                            </div>
                                            <button
                                                type="button"
                                                class="tw-rounded tw-bg-rose-500 tw-text-white tw-px-2 tw-py-1 tw-text-xs hover:tw-bg-rose-600"
                                                @click="removeImage(idx)"
                                            >
                                                Șterge
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p
                                    v-else
                                    class="tw-mt-3 tw-text-sm tw-text-slate-500"
                                >
                                    Nu există imagini încărcate.
                                </p>
                                <FormError :message="errors.images" />
                            </div>

                            <!-- ACTIONS -->
                            <div
                                class="tw-mt-10 tw-flex tw-items-center tw-gap-3"
                            >
                                <button
                                    :disabled="form.processing"
                                    @click="submit"
                                    class="tw-inline-flex tw-items-center tw-rounded-xl tw-bg-[var(--theme)] hover:tw-bg-[var(--theme2)] tw-text-white tw-font-semibold tw-px-5 tw-py-2.5 tw-transition disabled:tw-opacity-60"
                                >
                                    <span v-if="!form.processing"
                                        >Salvează</span
                                    >
                                    <span v-else>Se salvează…</span>
                                </button>
                                <button
                                    type="button"
                                    class="tw-inline-flex tw-items-center tw-rounded-xl tw-bg-white tw-border tw-border-slate-200 tw-text-slate-700 tw-font-medium tw-px-4 tw-py-2.5 hover:tw-bg-slate-50"
                                    @click="resetLocal"
                                >
                                    Resetează
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PREVIEW (live) -->
                <div class="tw-sticky tw-top-6">
                    <div
                        class="tw-rounded-3xl tw-bg-gradient-to-b from-slate-50 tw-to-white tw-ring-1 ring-black/5 tw-shadow-sm"
                    >
                        <div
                            class=" tw-p-2tw-flex tw-items-center tw-justify-between"
                        >
                            <!-- <inertia-link
                                :href="
                                    route('companies.show', props.company.id)
                                "
                                target="_blank"
                                rel="noopener"
                                class="tw-text-xs tw-font-medium tw-text-[var(--theme)] hover:tw-underline tw-flex tw-justify-end"
                            >
                                Vezi previzualizarea publică
                            </inertia-link> -->
                            <h2
                                class="tw-text-sm tw-font-semibold tw-text-slate-600"
                            >
                                Previzualizare telefon
                            </h2>
                        </div>

                        <div class="tw-border-t tw-border-slate-100">
                            <!-- folosim aceeași logică din pagina publică, condensată -->
                            <div class="tw-p-6">
                                <div class="tw-flex tw-items-start tw-gap-4">
                                    <div
                                        class="tw-h-16 tw-w-16 tw-rounded-2xl tw-bg-slate-100 tw-overflow-hidden tw-ring-1 ring-black/5 tw-flex tw-items-center tw-justify-center"
                                    >
                                        <img
                                            v-if="preview.logo"
                                            :src="toPublicUrl(preview.logo)"
                                            class="tw-h-full tw-w-full tw-object-contain"
                                        />
                                        <span
                                            v-else
                                            class="tw-text-lg tw-font-semibold tw-text-slate-500"
                                            >{{ initialsPreview }}</span
                                        >
                                    </div>
                                    <div class="tw-flex-1">
                                        <h3
                                            class="tw-text-xl tw-font-bold tw-text-slate-900"
                                        >
                                            {{ form.name || "Nume companie" }}
                                        </h3>
                                        <p
                                            class="tw-mt-1 tw-text-slate-600 tw-whitespace-pre-line"
                                            v-if="form.description"
                                        >
                                            {{ form.description }}
                                        </p>

                                        <div
                                            class="tw-mt-3 tw-flex tw-flex-wrap tw-gap-2"
                                        >
                                            <a
                                                v-if="form.website"
                                                :href="normalizedWebsite"
                                                target="_blank"
                                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-bg-[var(--theme)] tw-px-3 tw-py-1.5 tw-text-xs tw-font-medium tw-text-white"
                                                ><svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    class="tw-h-4 tw-w-4"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M13 7h4m0 0v4m0-4l-6 6"
                                                    />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M8 7h-1a3 3 0 00-3 3v7a3 3 0 003 3h7a3 3 0 003-3v-1"
                                                    />
                                                </svg>
                                                Vezi site firmă parteneră</a
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="tw-border-t tw-border-white/10 tw-p-8 tw-bg-[var(--theme2)] tw-ml-4 tw-mr-4 tw-rounded-3xl"
                            >
                                <dl class="tw-grid tw-grid-cols-1 tw-gap-6">
                                    <div
                                        class="tw-flex tw-items-start tw-gap-3"
                                    >
                                        <svg
                                            class="tw-h-5 tw-w-5 tw-text-white/70 tw-mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                        </svg>
                                        <div>
                                            <dt
                                                class="tw-text-xs tw-font-semibold tw-text-white"
                                            >
                                                Email
                                            </dt>
                                            <dd
                                                class="tw-text-sm tw-text-white/85"
                                            >
                                                {{ form.email || "—" }}
                                            </dd>
                                        </div>
                                    </div>

                                    <!-- Telefon -->
                                    <div
                                        class="tw-flex tw-items-start tw-gap-3"
                                    >
                                        <svg
                                            class="tw-h-5 tw-w-5 tw-text-white/70 tw-mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M3 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6v2a10 10 0 0010 10h2v-1a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-1C9.163 24 0 14.837 0 3V2a2 2 0 012-2h2a2 2 0 012 2v1z"
                                            />
                                        </svg>
                                        <div>
                                            <dt
                                                class="tw-text-xs tw-font-semibold tw-text-white"
                                            >
                                                Telefon
                                            </dt>
                                            <dd
                                                class="tw-text-sm tw-text-white/85"
                                            >
                                                {{ form.phone || "—" }}
                                            </dd>
                                        </div>
                                    </div>
                                    <div
                                        class="tw-flex tw-items-start tw-gap-3"
                                    >
                                        <svg
                                            class="tw-h-5 tw-w-5 tw-text-white/70 tw-mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 22s8-4.5 8-12a8 8 0 10-16 0c0 7.5 8 12 8 12z"
                                            />
                                        </svg>
                                        <div>
                                            <dt
                                                class="tw-text-xs tw-font-semibold tw-text-white"
                                            >
                                                Adresă
                                            </dt>
                                            <dd
                                                class="tw-text-sm tw-text-white/85"
                                            >
                                                {{ form.address || "—" }}
                                            </dd>
                                        </div>
                                    </div>
                                    <!-- Website -->
                                    <div
                                        class="tw-flex tw-items-start tw-gap-3"
                                    >
                                        <svg
                                            class="tw-h-5 tw-w-5 tw-text-white/70 tw-mt-0.5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 4a8 8 0 018 8h-3a5 5 0 10-5 5v3a8 8 0 010-16z"
                                            />
                                        </svg>
                                        <div>
                                            <dt
                                                class="tw-text-xs tw-font-semibold tw-text-white"
                                            >
                                                Website
                                            </dt>
                                            <dd
                                                class="tw-text-sm tw-text-white/85"
                                            >
                                                {{
                                                    prettyUrl(form.website) ||
                                                    "—"
                                                }}
                                            </dd>
                                        </div>
                                    </div>
                                </dl>
                            </div>

                            <div class="tw-p-6">
                                <div
                                    class="tw-overflow-hidden tw-rounded-2xl tw-ring-1 ring-black/5 tw-mb-5"
                                >
                                    <iframe
                                        v-if="mapEmbedSrc"
                                        :key="mapEmbedSrc"
                                        class="tw-w-full tw-h-[220px]"
                                        :src="mapEmbedSrc"
                                        style="border: 0"
                                        loading="lazy"
                                    ></iframe>
                                    <div
                                        v-else
                                        class="tw-h-[220px] tw-flex tw-items-center tw-justify-center tw-bg-slate-50 tw-text-slate-500"
                                    >
                                        Locația nu este disponibilă.
                                    </div>
                                </div>
                                <div>
                                    <h3
                                        class="tw-text-lg tw-font-semibold tw-text-slate-900"
                                    >
                                        Locație companie
                                    </h3>
                                    <p
                                        class="tw-mt-2 tw-text-sm tw-text-slate-600"
                                    >
                                        {{
                                            form.address ||
                                            "Adresă indisponibilă"
                                        }}
                                    </p>
                                </div>
                                <div class="tw-mt-4">
                                    <a
                                        :href="mapExternalLink"
                                        target="_blank"
                                        rel="noopener"
                                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-border tw-border-slate-200 tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-slate-700 hover:tw-bg-slate-50 tw-transition"
                                    >
                                        Deschide în Google Maps
                                        <svg
                                            class="tw-h-4 tw-w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M13 7h4m0 0v4m0-4l-6 6"
                                            />
                                        </svg>
                                    </a>
                                </div>

                                <div
                                    v-if="preview.images.length"
                                    class="tw-relative"
                                >
                                    <div
                                        class="tw-flex tw-items-center tw-justify-between tw-gap-4 tw-mb-4"
                                    >
                                        <h2
                                            class="tw-text-xl sm:tw-text-2xl tw-font-semibold tw-text-slate-900 tw-mt-12 tw-mb-8"
                                        >
                                            Galerie foto
                                        </h2>
                                        <div
                                            v-if="previewSlides.length"
                                            class="tw-flex tw-items-center tw-gap-2 tw-mt-12 tw-mb-8"
                                        >
                                            <button
                                                @click="pvPrev"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full hover:tw-bg-white tw-shadow tw-p-2 tw-ring-1 ring-black/5"
                                                :disabled="
                                                    !previewSlides.length
                                                "
                                                aria-label="Imaginea anterioară"
                                            >
                                                <svg
                                                    class="tw-h-5 tw-w-5"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M15 19l-7-7 7-7"
                                                    />
                                                </svg>
                                            </button>
                                            <button
                                                @click="pvNext"
                                                class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full hover:tw-bg-white tw-shadow tw-p-2 tw-ring-1 ring-black/5"
                                                :disabled="
                                                    !previewSlides.length
                                                "
                                                aria-label="Următoarea imagine"
                                            >
                                                <svg
                                                    class="tw-h-5 tw-w-5"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M9 5l7 7-7 7"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Viewport -->
                                    <div
                                        class="tw-overflow-hidden tw-rounded-xl tw-ring-1 ring-black/5"
                                        @mouseenter="pvPause = true"
                                        @mouseleave="pvPause = false"
                                    >
                                        <!-- Track -->
                                        <div
                                            class="tw-flex tw-duration-500 tw-ease-[cubic-bezier(.22,1,.36,1)]"
                                            :style="{
                                                transform: `translate3d(-${
                                                    pvIndex * (100 / perView)
                                                }%, 0, 0)`,
                                            }"
                                            style="will-change: transform"
                                        >
                                            <!-- Slide -->
                                            <div
                                                v-for="(
                                                    src, idx
                                                ) in previewSlides"
                                                :key="idx"
                                                class="tw-relative tw-shrink-0"
                                                :style="{
                                                    flex: `0 0 ${
                                                        100 / perView
                                                    }%`,
                                                }"
                                            >
                                                <div
                                                    class="tw-aspect-[4/3] tw-w-full tw-overflow-hidden"
                                                >
                                                    <img
                                                        :src="toPublicUrl(src)"
                                                        :alt="`preview ${
                                                            idx + 1
                                                        }`"
                                                        class="tw-h-full tw-w-full tw-object-cover"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Controls -->
                                    <div
                                        class="tw-mt-2 tw-flex tw-items-center tw-justify-between"
                                    >
                                        <!-- Dots (după pagini) -->
                                        <div class="tw-flex tw-gap-1.5">
                                            <button
                                                v-for="i in pvTotalPages"
                                                :key="i"
                                                @click="pvGo(i - 1)"
                                                :aria-label="`Pagina ${i}`"
                                                class="tw-h-2.5 tw-w-2.5 tw-rounded-full tw-transition"
                                                :class="
                                                    i - 1 === pvCurrentPage
                                                        ? 'tw-bg-blue-600'
                                                        : 'tw-bg-slate-300 hover:tw-bg-slate-400'
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>

                                <p
                                    v-else
                                    class="tw-text-xs tw-text-slate-500 tw-mt-8"
                                >
                                    Adaugă imagini pentru galerie.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </CompanyOwnerDashboardLayout>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import CompanyOwnerDashboardLayout from "@/Layouts/CompanyOwnerDashboardLayout.vue";
import { watch } from "vue";

const props = defineProps({
    company: { type: Object, required: true },
});

const galleryPaths = props.company?.gallery_images ?? [];

const form = useForm({
  name: props.company?.name ?? "",
  description: props.company?.description ?? "",
  website: props.company?.website ?? "",
  email: props.company?.email ?? "",
  phone: props.company?.phone ?? "",
  address: props.company?.address ?? "",
  latitude: props.company?.latitude ?? "",
  longitude: props.company?.longitude ?? "",
  logo: props.company?.logo,
  remove_logo: false,

  images_new: [],
  images_keep: [...galleryPaths],   // păstrăm path-urile existente
  images_order: [...galleryPaths],  // ordinea existentă
  images_remove: [],
});

const preview = reactive({
  logo: props.company?.logo || null,
  images: galleryPaths.map((path, idx) => ({
    key: `old-${idx}`,
    path,               // path-ul din S3
    url: path,          // îl vom converti la URL public în UI
    isNew: false,
  })),
});

/** Erori */
const errors = computed(() => form.errors || {});
const hasAnyError = computed(() => Object.keys(errors.value).length > 0);
const flatErrors = computed(() => {
    const out = {};
    for (const [k, v] of Object.entries(errors.value))
        out[k] = Array.isArray(v) ? v.join(", ") : v;
    return out;
});

/** Helpers UI */
const logoInput = ref(null);
const galleryInput = ref(null);

/** Previzualizări locale (logo + imagini) */


function onPickLogo(e) {
    const file = e.target.files?.[0];
    if (!file) return;
    form.logo = file;
    form.remove_logo = false;
    const reader = new FileReader();
    reader.onload = () => (preview.logo = reader.result);
    reader.readAsDataURL(file);
}

function removeLogo() {
    form.logo = null;
    form.remove_logo = true;
    preview.logo = null;
    if (logoInput.value) logoInput.value.value = "";
}

function onPickGallery(e) {
  const files = Array.from(e.target.files || []);
  if (!files.length) return;

  for (const f of files) {
    const key = `new-${cryptoRandom()}`;
    form.images_new.push(f); // pentru submit

    const reader = new FileReader();
    reader.onload = () => {
      preview.images.push({
        key,
        path: null,      // nu avem path încă
        file: f,         // IMPORTANT: ținem file și în preview
        url: reader.result, // dataURL pt afişare imediată
        isNew: true,
      });
    };
    reader.readAsDataURL(f);
  }

  if (galleryInput.value) galleryInput.value.value = "";
}

function toPublicUrl(val) {
  // dacă e deja dataURL sau URL absolut, întoarce-l ca atare
  if (!val) return "";
  if (/^data:|^blob:|^https?:\/\//i.test(val)) return val;

  // altfel e un path din S3 — prefixează cu base/cdn
  const base = import.meta.env.VITE_AWS_PUBLIC_URL || "/storage";
  return `${base}/${val}`;
}


function removeImage(idx) {
    const img = preview.images[idx];
    preview.images.splice(idx, 1);
    if (img.isNew) {
        const remainingNew = preview.images.filter((i) => i.isNew);
        form.images_new = remainingNew.map((_) => _.file).filter(Boolean);
    } else if (img.path) {
  form.images_remove.push(img.path);
  form.images_keep  = form.images_keep.filter((p) => p !== img.path);
 form.images_order = form.images_order.filter((p) => p !== img.path);
  }
}

function move(idx, dir) {
    const to = idx + dir;
    if (to < 0 || to >= preview.images.length) return;
    const arr = preview.images;
    [arr[idx], arr[to]] = [arr[to], arr[idx]];

    form.images_order = arr.filter(i => !i.isNew && i.path).map(i => i.path);
}

/** Previzualizare – inițiale */
const initialsPreview = computed(() => {
    const n = form.name?.trim() || "";
    if (!n) return "Logo";
    return n
        .split(/\s+/)
        .filter(Boolean)
        .map((w) => w[0])
        .slice(0, 2)
        .join("")
        .toUpperCase();
});

/** Map & website */
const normalizedWebsite = computed(() => normalizeUrl(form.website));

const mapEmbedSrc = computed(() => {
    const addr = (form.address || "").trim();
    if (addr) {
        return `https://www.google.com/maps?q=${encodeURIComponent(
            addr
        )}&z=14&output=embed`;
    }
    if (form.latitude && form.longitude) {
        return `https://www.google.com/maps?q=${form.latitude},${form.longitude}&z=14&output=embed`;
    }
    return "";
});

function normalizeUrl(url) {
    if (!url) return "";
    return /^https?:\/\//i.test(url) ? url : `https://${url}`;
}
function prettyUrl(url) {
    if (!url) return "";
    const u = url.replace(/^https?:\/\//i, "");
    return u.replace(/\/$/, "");
}
//COORDS
let addrTimer = null;
let addrAbort = null;

async function geocodeAddress(addr) {
  if (addrAbort) addrAbort.abort();
  addrAbort = new AbortController();

  const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
  const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(addr)}&key=${apiKey}`;

  const res = await fetch(url, { signal: addrAbort.signal });
  if (!res.ok) throw new Error(`Geocoding failed: ${res.status}`);
  const data = await res.json();

  if (data.status !== "OK" || !data.results.length) return null;

  const { lat, lng } = data.results[0].geometry.location;
  return { lat, lng };
}

watch(
  () => form.address,
  (addr) => {
    clearTimeout(addrTimer);

    if (!addr || !addr.trim()) {
      form.latitude = "";
      form.longitude = "";
      return;
    }

    addrTimer = setTimeout(async () => {
      try {
        const r = await geocodeAddress(addr.trim());
        if (r) {
          form.latitude = r.lat;
          form.longitude = r.lng;
        } else {
          form.latitude = "";
          form.longitude = "";
        }
      } catch (e) {
        console.error("Geocoding error", e);
      }
    }, 600);
  }
);



function submit() {
  // 1) construim ordinea COMPLETĂ exact ca în UI
  //    - pentru existente trimitem path-ul (img.path)
  //    - pentru noile fișiere trimitem "__new__:<idxInImagesNew>"
  const newIndexByKey = new Map();
  // pregătim images_new în ordinea în care apar în preview (NU după input-ul <input multiple>)
  const newFilesInUiOrder = [];
  preview.images.forEach((img) => {
    if (img.isNew && img.file) {
      newIndexByKey.set(img.key, newFilesInUiOrder.length);
      newFilesInUiOrder.push(img.file);
    }
  });

  const fullOrder = preview.images.map((img) => {
    if (img.isNew) {
      const idx = newIndexByKey.get(img.key);
      return `__new__:${idx}`;
    }
    return img.path; // existent
  });

  // 2) sincronizează vectorii „existente”
  const existingPathsInUiOrder = preview.images
    .filter(i => !i.isNew && i.path)
    .map(i => i.path);

  form.images_order = existingPathsInUiOrder; // dacă BE-ul încă folosește fallback pe images_order
  form.images_keep  = existingPathsInUiOrder; // ce a mai rămas din existente
  // form.images_remove deja se actualizează la removeImage()

  // 3) suprascriem images_new cu fișierele noi în ORDINEA din UI
  form.images_new = newFilesInUiOrder;

  // 4) Inertia: transform în multipart + atașăm images_order_full
  form.transform((data) => {
    const fd = new FormData();

    // adăugăm întâi câmpurile non-file
    for (const [k, v] of Object.entries(data)) {
      if (k === 'images_new') continue;  // le punem separat mai jos
      if (Array.isArray(v)) {
        v.forEach(val => fd.append(`${k}[]`, val));
      } else if (v !== null && v !== undefined) {
        fd.append(k, v);
      }
    }

    // ordinea completă (existente + placeholder pentru noi)
    fullOrder.forEach(val => fd.append('images_order_full[]', val));

    // fișierele noi în ordinea din UI, astfel încât __new__:<idx> să corespundă
    data.images_new.forEach(f => fd.append('images_new[]', f));

    return fd;
  });

  form.post(route('company-owner.profile.update', props.company.id), {
    preserveScroll: true,
  });
}


function resetLocal() {
    form.reset();
    preview.logo = props.company?.logo_url || null;
    preview.images = (props.company?.images ?? []).map((img) => ({
        key: `old-${img.id}`,
        id: img.id,
        url: img.url,
        isNew: false,
    }));
}

/** utils */
function cryptoRandom() {
    if (window.crypto?.getRandomValues) {
        const a = new Uint32Array(1);
        window.crypto.getRandomValues(a);
        return a[0].toString(36);
    }
    return Math.random().toString(36).slice(2);
}

/** Slides pentru preview: doar URL-urile din preview.images */
const previewSlides = computed(() => preview.images.map((i) => i.url));

/** Carousel state (preview) */
const pvIndex = ref(0);
const pvPause = ref(false);

// per view mic (sidebar): 1 pe mobil/tabletă, 2 pe ecrane mai late
const perView = ref(1);
function updatePerView() {
    perView.value = 1; // sm
}

onMounted(() => {
    updatePerView();
    window.addEventListener("resize", updatePerView);
});
onBeforeUnmount(() => window.removeEventListener("resize", updatePerView));

function pvPrev() {
    const n = previewSlides.value.length;
    if (!n) return;
    pvIndex.value = Math.max(0, pvIndex.value - perView.value);
}
function pvNext() {
    const n = previewSlides.value.length;
    if (!n) return;
    const lastStart = Math.max(0, n - perView.value);
    pvIndex.value = Math.min(lastStart, pvIndex.value + perView.value);
}
function pvGo(page) {
    pvIndex.value = Math.min(
        previewSlides.value.length - 1,
        page * perView.value
    );
}

const pvTotalPages = computed(() =>
    Math.max(1, Math.ceil(previewSlides.value.length / perView.value))
);
const pvCurrentPage = computed(() => Math.floor(pvIndex.value / perView.value));

// autoplay ușor
let pvTimer = null;
function pvStart() {
    pvStop();
    pvTimer = setInterval(() => {
        if (pvPause.value || !previewSlides.value.length) return;
        const step = perView.value;
        const lastStart = Math.max(0, previewSlides.value.length - step);
        pvIndex.value = pvIndex.value >= lastStart ? 0 : pvIndex.value + step;
    }, 3500);
}
function pvStop() {
    if (pvTimer) {
        clearInterval(pvTimer);
        pvTimer = null;
    }
}
onMounted(pvStart);
onBeforeUnmount(pvStop);
</script>

<script>
export default {
    methods: {
        prettyUrl(url) {
            if (!url) return "";
            const u = url.replace(/^https?:\/\//i, "");
            return u.replace(/\/$/, "");
        },
    },
};
</script>
