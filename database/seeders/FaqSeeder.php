<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FaqCategory;
use App\Models\Faq;
use Illuminate\Support\Str;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Inainte de inchiriere',
                'faqs' => [
                    ['question' => 'Ce este RENTeaza.ro?', 'answer' => 'RENTeaza.ro este o platforma digitala de inchiriere auto intre persoane (peer-to-peer), unde utilizatorii pot in'],
                    ['question' => 'Cine poate inchiria o masina?', 'answer' => 'Orice persoana cu varsta minima de 21 de ani, care are un permis de conducere valabil si un cont verificat i'],
                    ['question' => 'Este nevoie de garantie?', 'answer' => 'Pentru unele vehicule, da. Garantia este stabilita de catre proprietar si se blocheaza temporar prin platforma'],
                    ['question' => 'Cum verific disponibilitatea unei masini?', 'answer' => "Poti folosi filtrul de cautare dupa locatie, data si tip de vehicul. Daca masina este marcata cu 'rezervare insta'"],
                ],
            ],
            [
                'name' => 'Contul meu si inregistrare',
                'faqs' => [
                    ['question' => 'Cum imi creez un cont?', 'answer' => "Acceseaza butonul 'Cont' din coltul dreapta sus, completeaza datele de baza si incarca actele necesare: ca"],
                    ['question' => 'Este obligatorie verificarea contului?', 'answer' => 'Da. Pentru siguranta tuturor utilizatorilor, verificarea este necesara inainte de prima inchiriere sau listare de'],
                    ['question' => 'Pot folosi acelasi cont ca utilizator si ca proprietar?', 'answer' => 'Da. Cu acelasi cont poti atat sa inchiriezi vehicule, cat si sa iti listezi propriile masini spre inchiriere.'],
                ],
            ],
            [
                'name' => 'Inchirierea propriu-zisa',
                'faqs' => [
                    ['question' => 'Cum functioneaza plata?', 'answer' => 'Plata se face online prin platforma. Suma este blocata intr-un cont escrow si este eliberata catre proprietar d'],
                    ['question' => 'Cine raspunde in caz de dauna?', 'answer' => 'Utilizatorul este responsabil pentru vehicul pe toata durata inchirierii. Se recomanda fotografii la preluare si p'],
                    ['question' => 'Pot modifica sau anula o rezervare?', 'answer' => 'Da. Fiecare proprietar isi stabileste propria politica de anulare. Conditiile sunt afisate clar inainte de rezervar'],
                ],
            ],
            [
                'name' => 'Pentru proprietari',
                'faqs' => [
                    ['question' => 'Cum listez un vehicul?', 'answer' => "Dupa crearea contului si verificare, accesezi 'Adauga vehicul', completezi informatiile, incarci actele (RCA, I"],
                    ['question' => 'Ce documente sunt necesare?', 'answer' => 'Pentru listare: talonul vehiculului, asigurare RCA valabila si ITP activ. Optional, poti adauga cazierul auto sa'],
                    ['question' => 'Cati bani pot castiga?', 'answer' => 'Depinde de tipul vehiculului, perioada de inchiriere si tariful ales. Platforma retine un comision, iar restul sum'],
                ],
            ],
            [
                'name' => 'Siguranta, legalitate si suport',
                'faqs' => [
                    ['question' => 'Este legal sa inchiriez prin RENTeaza.ro?', 'answer' => 'Da. Inchirierea intre persoane fizice sau juridice este permisa de lege, iar platforma genereaza automat un c'],
                    ['question' => 'Cum se protejeaza platforma de fraude?', 'answer' => 'Toti utilizatorii trec printr-un proces de verificare, iar platile sunt procesate securizat prin sistem escrow. Con'],
                    ['question' => 'Ce fac daca am o problema?', 'answer' => "Poti accesa sectiunea 'Suport' din platforma sau ne poti contacta prin email, telefon sau formular. E"],
                ],
            ],
            [
                'name' => 'Securitate si date personale',
                'faqs' => [
                    ['question' => 'Datele mele sunt in siguranta?', 'answer' => 'Da. RENTeaza.ro respecta regulamentele privind protectia datelor. Datele sunt stocate in siguranta si nu su'],
                    ['question' => 'Cine are acces la datele mele?', 'answer' => 'Doar tu si echipa administrativa RENTeaza.ro, in scopul verificarii si functionarii corecte a platformei.'],
                    ['question' => 'Pot solicita stergerea contului meu?', 'answer' => 'Da. Poti cere oricand stergerea contului si a datelor asociate, din setarile contului sau printr-o solicitare scris'],
                ],
            ],
            [
                'name' => 'Aplicatie mobila si notificari',
                'faqs' => [
                    ['question' => 'Exista aplicatie pentru mobil?', 'answer' => 'Platforma functioneaza deja foarte bine pe mobil. In curand va fi lansata si aplicatia dedicata Android/iOS.'],
                    ['question' => 'Voi primi notificari pentru rezervari sau mesaje?', 'answer' => 'Da. Vei primi notificari pe email si, daca folosesti aplicatia, si notificari push.'],
                ],
            ],
            [
                'name' => 'Situatii speciale',
                'faqs' => [
                    ['question' => 'Pot inchiria fara card bancar?', 'answer' => 'Nu. Pentru siguranta, toate platile se fac online, prin card.'],
                    ['question' => 'Pot inchiria pentru firma / persoana juridica?', 'answer' => 'Da. In timpul rezervarii poti solicita facturare pe firma.'],
                    ['question' => 'Pot inchiria o masina pentru alt sofer?', 'answer' => 'Nu. Persoana care face rezervarea trebuie sa fie si cea care conduce masina.'],
                ],
            ],
            [
                'name' => 'Despre platforma si dezvoltare',
                'faqs' => [
                    ['question' => 'Este RENTeaza.ro o companie romaneasca?', 'answer' => 'Da. Platforma este dezvoltata si operata de RENTEAZA MOBILITY SOLUTIONS SRL, o companie inregistr'],
                    ['question' => 'Pot recomanda platforma si castiga ceva?', 'answer' => 'Da. In curand vom lansa un sistem de recomandari prin care poti primi beneficii.'],
                    ['question' => 'Ce urmeaza sa adaugati in platforma?', 'answer' => 'Lucram la integrarea cu asiguratori, detectare daune AI, livrare auto la adresa si solutii pentru firme (SaaS).'],
                ],
            ],
            [
                'name' => 'Probleme tehnice',
                'faqs' => [
                    ['question' => 'Nu pot finaliza o rezervare. Ce pot face?', 'answer' => 'Verifica daca ai completat toate campurile si ai cardul valid. Daca problema persista, contacteaza echipa de'],
                    ['question' => 'Cum pot modifica datele contului?', 'answer' => "Din sectiunea 'Profil'. Pentru date sensibile, contacteaza echipa noastra."],
                    ['question' => 'Am uitat parola. Cum o recuperez?', 'answer' => "Foloseste optiunea 'Ai uitat parola?' de pe pagina de autentificare."],
                ],
            ],
            [
                'name' => 'Locatie si disponibilitate',
                'faqs' => [
                    ['question' => 'Unde pot inchiria masini cu RENTeaza.ro?', 'answer' => 'Platforma este activa in toata Romania. Ofertele depind de orasul unde utilizatorii listeaza vehicule.'],
                    ['question' => 'Pot inchiria o masina intr-un oras si returna in altul?', 'answer' => 'Momentan nu. Predarea trebuie facuta in acelasi loc in care a fost preluata masina, cu exceptia cazurilor sp'],
                ],
            ],
            [
                'name' => 'B2B si companii',
                'faqs' => [
                    ['question' => 'Pot lista mai multe vehicule ca firma?', 'answer' => 'Da. RENTeaza permite conturi pentru companii care pot lista flote intregi.'],
                    ['question' => 'Oferiti solutii personalizate pentru flote?', 'answer' => 'Da. Platforma este disponibila si ca SaaS, personalizabila pentru firme de rent-a-car sau livrari.'],
                    ['question' => 'Emiteti facturi?', 'answer' => 'Da. Factura este generata automat dupa fiecare rezervare, in functie de tipul contului.'],
                ],
            ],
            [
                'name' => 'Beneficii si fidelizare',
                'faqs' => [
                    ['question' => 'Exista reduceri sau coduri promotionale?', 'answer' => 'Da. Periodic oferim reduceri prin campanii si newsletter.'],
                    ['question' => 'Ce este RENTeaza CLUB?', 'answer' => 'Este un program de fidelizare cu beneficii speciale, test drive-uri si oferte dedicate pentru utilizatorii activi.'],
                ],
            ],
            [
                'name' => 'Contract si responsabilitati',
                'faqs' => [
                    ['question' => 'Cine semneaza contractul?', 'answer' => 'Contractul este semnat digital de catre chirias si proprietar, la momentul rezervarii.'],
                    ['question' => 'Pot cere copie dupa contract?', 'answer' => 'Da. Contractul este salvat automat in cont si poate fi descarcat oricand.'],
                    ['question' => 'Ce se intampla daca intarzii cu predarea?', 'answer' => 'Se poate aplica o taxa suplimentara. Detaliile sunt specificate in contractul de rezervare.'],
                ],
            ],
            [
                'name' => 'Situatii neprevazute',
                'faqs' => [
                    ['question' => 'Ce fac daca masina are o problema tehnica in timpul inchirierii?', 'answer' => 'Contacteaza imediat proprietarul si echipa RENTeaza. Documenteaza situatia si urmeaza pasii recomandat'],
                    ['question' => 'Ce se intampla in caz de accident?', 'answer' => 'Completeaza constatarea amiabila sau contacteaza Politia daca este cazul. Apoi anunta echipa noastra.'],
                    ['question' => 'Ce se intampla daca returnez masina cu combustibil mai putin?', 'answer' => 'Se poate aplica o taxa de realimentare, in functie de politica setata de proprietar.'],
                ],
            ],
            [
                'name' => 'Fiscalitate si documente',
                'faqs' => [
                    ['question' => 'Trebuie sa declar veniturile obtinute ca proprietar?', 'answer' => 'Da. Persoanele fizice trebuie sa declare veniturile din inchirieri conform legislatiei fiscale in vigoare.'],
                    ['question' => 'Ce valoare legala are semnatura digitala?', 'answer' => 'Semnatura electronica folosita pe platforma este legala si opozabila conform legii 455/2001.'],
                    ['question' => 'Pot solicita dovada de rezervare pentru contabilitate?', 'answer' => 'Da. Poti descarca oricand factura si contractul aferent fiecarei rezervari din contul tau.'],
                ],
            ],
            [
                'name' => 'Calatorii in afara orasului sau tarii',
                'faqs' => [
                    ['question' => 'Pot iesi cu masina din oras?', 'answer' => 'Da, daca proprietarul permite. Verifica acest aspect in descrierea anuntului.'],
                    ['question' => 'Pot iesi cu masina din tara?', 'answer' => 'Doar cu acordul scris al proprietarului si documente suplimentare (ex: procura notariala).'],
                ],
            ],
            [
                'name' => 'Preluare si livrare vehicul',
                'faqs' => [
                    ['question' => 'Pot primi masina la adresa?', 'answer' => 'Da, daca proprietarul ofera livrare. Aceasta optiune este vizibila in pagina de rezervare.'],
                    ['question' => 'Unde se face predarea si returnarea masinii?', 'answer' => 'Locatia este stabilita de proprietar si confirmata la rezervare.'],
                    ['question' => 'Ce se intampla daca intarzii la preluare sau predare?', 'answer' => 'Anunta din timp. Se pot aplica taxe de intarziere conform politicii proprietarului.'],
                ],
            ],
            [
                'name' => 'Preturi si comisioane',
                'faqs' => [
                    ['question' => 'Cine stabileste pretul inchirierii?', 'answer' => 'Proprietarul isi stabileste liber tariful. Platforma poate oferi sugestii.'],
                    ['question' => 'Se aplica comisioane?', 'answer' => 'Da, RENTeaza percepe un comision pe rezervare. Valoarea exacta este afisata inainte de finalizare.'],
                    ['question' => 'Exista tarife diferite pentru weekend sau sezon?', 'answer' => 'Da, unele vehicule pot avea preturi speciale in functie de perioada.'],
                ],
            ],
            [
                'name' => 'Recenzii si ratinguri',
                'faqs' => [
                    ['question' => 'Cum functioneaza recenziile?', 'answer' => 'Dupa fiecare inchiriere, atat chiriasul cat si proprietarul pot lasa un rating si un comentariu public.'],
                    ['question' => 'Pot contesta o recenzie nedreapta?', 'answer' => 'Da. O poti raporta, iar echipa RENTeaza va analiza situatia.'],
                    ['question' => 'Ce inseamna utilizator de incredere?', 'answer' => 'Este un badge acordat utilizatorilor activi, cu comportament corect si istoric pozitiv.'],
                ],
            ],
        ];

        foreach ($data as $item) {
            $category = FaqCategory::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
            ]);

            foreach ($item['faqs'] as $faq) {
                Faq::create([
                    'faq_category_id' => $category->id,
                    'question'    => $faq['question'],
                    'answer'      => $faq['answer'],
                    'is_active'   => true,
                ]);
            }
        }
    }
}
