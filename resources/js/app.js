document.addEventListener('DOMContentLoaded', function () {
    // Sidebar Navigation Toggle
    const btnDashboard = document.getElementById('btn-dashboard');
    const btnNlRecords = document.getElementById('btn-nl-records');
    const dashboardSection = document.getElementById('dashboard-section');
    const nlRecordsSection = document.getElementById('nl-records-section');

    function showDashboard() {
        dashboardSection.style.display = 'block';
        nlRecordsSection.style.display = 'none';
        btnDashboard.classList.add('active');
        btnNlRecords.classList.remove('active');
    }

    function showNlRecords() {
        dashboardSection.style.display = 'none';
        nlRecordsSection.style.display = 'block';
        btnDashboard.classList.remove('active');
        btnNlRecords.classList.add('active');
    }

    if (btnDashboard && btnNlRecords && dashboardSection && nlRecordsSection) {
        btnDashboard.addEventListener('click', showDashboard);
        btnNlRecords.addEventListener('click', showNlRecords);
    }

    // Admin Login functionality
    const adminButton = document.querySelector('.adminLoginButton');
    const loginDialog = document.querySelector('.loginDialog');
    const closeModal = document.querySelector('.closeModal');
    if (adminButton && loginDialog) {
        adminButton.addEventListener('click', function () {
            loginDialog.showModal();
        });
    }

    if (closeModal && loginDialog) {
        closeModal.addEventListener('click', function () {
            loginDialog.close();
        });
    }

    // Add Record Dialog

    const addRecordButton = document.querySelector('.addRecordButton');
    const addRecordDialog = document.querySelector('.addRecordDialog');

    const locationCsv = `BARANGAY,MUNICIPALITY,PROVINCE
Betes,Aliaga,Nueva Ecija
Bibiclat,Aliaga,Nueva Ecija
Bucot,Aliaga,Nueva Ecija
La Purisima,Aliaga,Nueva Ecija
Magsaysay,Aliaga,Nueva Ecija
Macabucod,Aliaga,Nueva Ecija
Pantoc,Aliaga,Nueva Ecija
Poblacion Centro,Aliaga,Nueva Ecija
Poblacion East I,Aliaga,Nueva Ecija
Poblacion East II,Aliaga,Nueva Ecija
Poblacion West III,Aliaga,Nueva Ecija
Poblacion West IV,Aliaga,Nueva Ecija
San Carlos,Aliaga,Nueva Ecija
San Emiliano,Aliaga,Nueva Ecija
San Eustacio,Aliaga,Nueva Ecija
San Felipe Bata,Aliaga,Nueva Ecija
San Felipe Matanda,Aliaga,Nueva Ecija
San Juan,Aliaga,Nueva Ecija
San Pablo Bata,Aliaga,Nueva Ecija
San Pablo Matanda,Aliaga,Nueva Ecija
Santa Monica,Aliaga,Nueva Ecija
Santiago,Aliaga,Nueva Ecija
Santo Rosario,Aliaga,Nueva Ecija
Santo Tomas,Aliaga,Nueva Ecija
Sunson,Aliaga,Nueva Ecija
Umangan,Aliaga,Nueva Ecija
Antipolo,Bongabon,Nueva Ecija
Ariendo,Bongabon,Nueva Ecija
Bantug,Bongabon,Nueva Ecija
Calaanan,Bongabon,Nueva Ecija
Commercial,Bongabon,Nueva Ecija
Cruz,Bongabon,Nueva Ecija
Digmala,Bongabon,Nueva Ecija
Curva,Bongabon,Nueva Ecija
Kaingin,Bongabon,Nueva Ecija
Labi,Bongabon,Nueva Ecija
Larcon,Bongabon,Nueva Ecija
Lusok,Bongabon,Nueva Ecija
Macabaclay,Bongabon,Nueva Ecija
Magtanggol,Bongabon,Nueva Ecija
Mantile,Bongabon,Nueva Ecija
Olivete,Bongabon,Nueva Ecija
Palo Maria,Bongabon,Nueva Ecija
Pesa,Bongabon,Nueva Ecija
Rizal,Bongabon,Nueva Ecija
Sampalucan,Bongabon,Nueva Ecija
San Roque,Bongabon,Nueva Ecija
Santor,Bongabon,Nueva Ecija
Sinipit,Bongabon,Nueva Ecija
Sisilang na Ligaya,Bongabon,Nueva Ecija
Social,Bongabon,Nueva Ecija
Tugatug,Bongabon,Nueva Ecija
Tulay na Bato,Bongabon,Nueva Ecija
Vega,Bongabon,Nueva Ecija
Aduas Centro,City of Cabanatuan,Nueva Ecija
Bagong Sikat,City of Cabanatuan,Nueva Ecija
Bagong Buhay,City of Cabanatuan,Nueva Ecija
Bakero,City of Cabanatuan,Nueva Ecija
Bakod Bayan,City of Cabanatuan,Nueva Ecija
Balite,City of Cabanatuan,Nueva Ecija
Bangad,City of Cabanatuan,Nueva Ecija
Bantug Bulalo,City of Cabanatuan,Nueva Ecija
Bantug Norte,City of Cabanatuan,Nueva Ecija
Barlis,City of Cabanatuan,Nueva Ecija
Barrera District,City of Cabanatuan,Nueva Ecija
Bernardo District,City of Cabanatuan,Nueva Ecija
Bitas,City of Cabanatuan,Nueva Ecija
Bonifacio District,City of Cabanatuan,Nueva Ecija
Buliran,City of Cabanatuan,Nueva Ecija
Caalibangbangan,City of Cabanatuan,Nueva Ecija
Cabu,City of Cabanatuan,Nueva Ecija
Campo Tinio,City of Cabanatuan,Nueva Ecija
Kapitan Pepe,City of Cabanatuan,Nueva Ecija
Cinco-Cinco,City of Cabanatuan,Nueva Ecija
City Supermarket,City of Cabanatuan,Nueva Ecija
Caudillo,City of Cabanatuan,Nueva Ecija
Communal,City of Cabanatuan,Nueva Ecija
Cruz Roja,City of Cabanatuan,Nueva Ecija
Daang Sarile,City of Cabanatuan,Nueva Ecija
Dalampang,City of Cabanatuan,Nueva Ecija
Dicarma,City of Cabanatuan,Nueva Ecija
Dimasalang,City of Cabanatuan,Nueva Ecija
Dionisio S. Garcia,City of Cabanatuan,Nueva Ecija
Fatima,City of Cabanatuan,Nueva Ecija
General Luna,City of Cabanatuan,Nueva Ecija
Ibabao Bana,City of Cabanatuan,Nueva Ecija
Imelda District,City of Cabanatuan,Nueva Ecija
Isla,City of Cabanatuan,Nueva Ecija
Calawagan,City of Cabanatuan,Nueva Ecija
Kalikid Norte,City of Cabanatuan,Nueva Ecija
Kalikid Sur,City of Cabanatuan,Nueva Ecija
Lagare,City of Cabanatuan,Nueva Ecija
M. S. Garcia,City of Cabanatuan,Nueva Ecija
Mabini Extension,City of Cabanatuan,Nueva Ecija
Mabini Homesite,City of Cabanatuan,Nueva Ecija
Macatbong,City of Cabanatuan,Nueva Ecija
Magsaysay District,City of Cabanatuan,Nueva Ecija
Matadero,City of Cabanatuan,Nueva Ecija
Lourdes,City of Cabanatuan,Nueva Ecija
Mayapyap Norte,City of Cabanatuan,Nueva Ecija
Mayapyap Sur,City of Cabanatuan,Nueva Ecija
Melojavilla,City of Cabanatuan,Nueva Ecija
Obrero,City of Cabanatuan,Nueva Ecija
Padre Crisostomo,City of Cabanatuan,Nueva Ecija
Pagas,City of Cabanatuan,Nueva Ecija
Palagay,City of Cabanatuan,Nueva Ecija
Pamaldan,City of Cabanatuan,Nueva Ecija
Pangatian,City of Cabanatuan,Nueva Ecija
Patalac,City of Cabanatuan,Nueva Ecija
Polilio,City of Cabanatuan,Nueva Ecija
Pula,City of Cabanatuan,Nueva Ecija
Quezon District,City of Cabanatuan,Nueva Ecija
Rizdelis,City of Cabanatuan,Nueva Ecija
Samon,City of Cabanatuan,Nueva Ecija
San Isidro,City of Cabanatuan,Nueva Ecija
San Josef Norte,City of Cabanatuan,Nueva Ecija
San Josef Sur,City of Cabanatuan,Nueva Ecija
San Juan Pob.,City of Cabanatuan,Nueva Ecija
San Roque Norte,City of Cabanatuan,Nueva Ecija
San Roque Sur,City of Cabanatuan,Nueva Ecija
Sanbermicristi,City of Cabanatuan,Nueva Ecija
Sangitan,City of Cabanatuan,Nueva Ecija
Santa Arcadia,City of Cabanatuan,Nueva Ecija
Sumacab Norte,City of Cabanatuan,Nueva Ecija
Valdefuente,City of Cabanatuan,Nueva Ecija
Valle Cruz,City of Cabanatuan,Nueva Ecija
Vijandre District,City of Cabanatuan,Nueva Ecija
Villa Ofelia-Caridad,City of Cabanatuan,Nueva Ecija
Zulueta District,City of Cabanatuan,Nueva Ecija
Nabao,City of Cabanatuan,Nueva Ecija
Padre Burgos,City of Cabanatuan,Nueva Ecija
Talipapa,City of Cabanatuan,Nueva Ecija
Aduas Norte,City of Cabanatuan,Nueva Ecija
Aduas Sur,City of Cabanatuan,Nueva Ecija
"Hermogenes C. Concepcion, Sr.",City of Cabanatuan,Nueva Ecija
Sapang,City of Cabanatuan,Nueva Ecija
Sumacab Este,City of Cabanatuan,Nueva Ecija
Sumacab South,City of Cabanatuan,Nueva Ecija
Caridad,City of Cabanatuan,Nueva Ecija
Magsaysay South,City of Cabanatuan,Nueva Ecija
Maria Theresa,City of Cabanatuan,Nueva Ecija
Sangitan East,City of Cabanatuan,Nueva Ecija
Santo Niño,City of Cabanatuan,Nueva Ecija
Bagong Buhay,Cabiao,Nueva Ecija
Bagong Sikat,Cabiao,Nueva Ecija
Bagong Silang,Cabiao,Nueva Ecija
Concepcion,Cabiao,Nueva Ecija
Entablado,Cabiao,Nueva Ecija
Maligaya,Cabiao,Nueva Ecija
Natividad North,Cabiao,Nueva Ecija
Natividad South,Cabiao,Nueva Ecija
Palasinan,Cabiao,Nueva Ecija
San Antonio,Cabiao,Nueva Ecija
San Fernando Norte,Cabiao,Nueva Ecija
San Fernando Sur,Cabiao,Nueva Ecija
San Gregorio,Cabiao,Nueva Ecija
San Juan North,Cabiao,Nueva Ecija
San Juan South,Cabiao,Nueva Ecija
San Roque,Cabiao,Nueva Ecija
San Vicente,Cabiao,Nueva Ecija
Santa Rita,Cabiao,Nueva Ecija
Sinipit,Cabiao,Nueva Ecija
Polilio,Cabiao,Nueva Ecija
San Carlos,Cabiao,Nueva Ecija
Santa Isabel,Cabiao,Nueva Ecija
Santa Ines,Cabiao,Nueva Ecija
R.A.Padilla,Carranglan,Nueva Ecija
Bantug,Carranglan,Nueva Ecija
Bunga,Carranglan,Nueva Ecija
Burgos,Carranglan,Nueva Ecija
Capintalan,Carranglan,Nueva Ecija
Joson,Carranglan,Nueva Ecija
General Luna,Carranglan,Nueva Ecija
Minuli,Carranglan,Nueva Ecija
Piut,Carranglan,Nueva Ecija
Puncan,Carranglan,Nueva Ecija
Putlan,Carranglan,Nueva Ecija
Salazar,Carranglan,Nueva Ecija
San Agustin,Carranglan,Nueva Ecija
T. L. Padilla Pob.,Carranglan,Nueva Ecija
F. C. Otic Pob.,Carranglan,Nueva Ecija
D. L. Maglanoc Pob.,Carranglan,Nueva Ecija
G. S. Rosario Pob.,Carranglan,Nueva Ecija
Baloy,Cuyapo,Nueva Ecija
Bambanaba,Cuyapo,Nueva Ecija
Bantug,Cuyapo,Nueva Ecija
Bentigan,Cuyapo,Nueva Ecija
Bibiclat,Cuyapo,Nueva Ecija
Bonifacio,Cuyapo,Nueva Ecija
Bued,Cuyapo,Nueva Ecija
Bulala,Cuyapo,Nueva Ecija
Burgos,Cuyapo,Nueva Ecija
Cabileo,Cuyapo,Nueva Ecija
Cabatuan,Cuyapo,Nueva Ecija
Cacapasan,Cuyapo,Nueva Ecija
Calancuasan Norte,Cuyapo,Nueva Ecija
Calancuasan Sur,Cuyapo,Nueva Ecija
Colosboa,Cuyapo,Nueva Ecija
Columbitin,Cuyapo,Nueva Ecija
Curva,Cuyapo,Nueva Ecija
District I,Cuyapo,Nueva Ecija
District II,Cuyapo,Nueva Ecija
District IV,Cuyapo,Nueva Ecija
District V,Cuyapo,Nueva Ecija
District VI,Cuyapo,Nueva Ecija
District VII,Cuyapo,Nueva Ecija
District VIII,Cuyapo,Nueva Ecija
Landig,Cuyapo,Nueva Ecija
Latap,Cuyapo,Nueva Ecija
Loob,Cuyapo,Nueva Ecija
Luna,Cuyapo,Nueva Ecija
Malbeg-Patalan,Cuyapo,Nueva Ecija
Malineng,Cuyapo,Nueva Ecija
Matindeg,Cuyapo,Nueva Ecija
Maycaban,Cuyapo,Nueva Ecija
Nagcuralan,Cuyapo,Nueva Ecija
Nagmisahan,Cuyapo,Nueva Ecija
Paitan Norte,Cuyapo,Nueva Ecija
Paitan Sur,Cuyapo,Nueva Ecija
Piglisan,Cuyapo,Nueva Ecija
Pugo,Cuyapo,Nueva Ecija
Rizal,Cuyapo,Nueva Ecija
Sabit,Cuyapo,Nueva Ecija
Salagusog,Cuyapo,Nueva Ecija
San Antonio,Cuyapo,Nueva Ecija
San Jose,Cuyapo,Nueva Ecija
San Juan,Cuyapo,Nueva Ecija
Santa Clara,Cuyapo,Nueva Ecija
Santa Cruz,Cuyapo,Nueva Ecija
Simimbaan,Cuyapo,Nueva Ecija
Tagtagumbao,Cuyapo,Nueva Ecija
Tutuloy,Cuyapo,Nueva Ecija
Ungab,Cuyapo,Nueva Ecija
Villaflores,Cuyapo,Nueva Ecija
Bagong Sikat,Gabaldon,Nueva Ecija
Bagting,Gabaldon,Nueva Ecija
Bantug,Gabaldon,Nueva Ecija
Bitulok,Gabaldon,Nueva Ecija
Bugnan,Gabaldon,Nueva Ecija
Calabasa,Gabaldon,Nueva Ecija
Camachile,Gabaldon,Nueva Ecija
Cuyapa,Gabaldon,Nueva Ecija
Ligaya,Gabaldon,Nueva Ecija
Macasandal,Gabaldon,Nueva Ecija
Malinao,Gabaldon,Nueva Ecija
Pantoc,Gabaldon,Nueva Ecija
Pinamalisan,Gabaldon,Nueva Ecija
South Poblacion,Gabaldon,Nueva Ecija
Sawmill,Gabaldon,Nueva Ecija
Tagumpay,Gabaldon,Nueva Ecija
Bayanihan,City of Gapan,Nueva Ecija
Bulak,City of Gapan,Nueva Ecija
Kapalangan,City of Gapan,Nueva Ecija
Mahipon,City of Gapan,Nueva Ecija
Malimba,City of Gapan,Nueva Ecija
Mangino,City of Gapan,Nueva Ecija
Marelo,City of Gapan,Nueva Ecija
Pambuan,City of Gapan,Nueva Ecija
Parcutela,City of Gapan,Nueva Ecija
San Lorenzo,City of Gapan,Nueva Ecija
San Nicolas,City of Gapan,Nueva Ecija
San Roque,City of Gapan,Nueva Ecija
San Vicente,City of Gapan,Nueva Ecija
Santa Cruz,City of Gapan,Nueva Ecija
Santo Cristo Norte,City of Gapan,Nueva Ecija
Santo Cristo Sur,City of Gapan,Nueva Ecija
Santo Niño,City of Gapan,Nueva Ecija
Makabaclay,City of Gapan,Nueva Ecija
Balante,City of Gapan,Nueva Ecija
Bungo,City of Gapan,Nueva Ecija
Mabunga,City of Gapan,Nueva Ecija
Maburak,City of Gapan,Nueva Ecija
Puting Tubig,City of Gapan,Nueva Ecija
Balangkare Norte,General Mamerto Natividad,Nueva Ecija
Balangkare Sur,General Mamerto Natividad,Nueva Ecija
Balaring,General Mamerto Natividad,Nueva Ecija
Belen,General Mamerto Natividad,Nueva Ecija
Bravo,General Mamerto Natividad,Nueva Ecija
Burol,General Mamerto Natividad,Nueva Ecija
Kabulihan,General Mamerto Natividad,Nueva Ecija
Mag-asawang Sampaloc,General Mamerto Natividad,Nueva Ecija
Manarog,General Mamerto Natividad,Nueva Ecija
Mataas na Kahoy,General Mamerto Natividad,Nueva Ecija
Panacsac,General Mamerto Natividad,Nueva Ecija
Picaleon,General Mamerto Natividad,Nueva Ecija
Pinahan,General Mamerto Natividad,Nueva Ecija
Platero,General Mamerto Natividad,Nueva Ecija
Poblacion,General Mamerto Natividad,Nueva Ecija
Pula,General Mamerto Natividad,Nueva Ecija
Pulong Singkamas,General Mamerto Natividad,Nueva Ecija
Sapang Bato,General Mamerto Natividad,Nueva Ecija
Talabutab Norte,General Mamerto Natividad,Nueva Ecija
Talabutab Sur,General Mamerto Natividad,Nueva Ecija
Bago,General Tinio,Nueva Ecija
Concepcion,General Tinio,Nueva Ecija
Nazareth,General Tinio,Nueva Ecija
Padolina,General Tinio,Nueva Ecija
Pias,General Tinio,Nueva Ecija
San Pedro,General Tinio,Nueva Ecija
Poblacion East,General Tinio,Nueva Ecija
Poblacion West,General Tinio,Nueva Ecija
Rio Chico,General Tinio,Nueva Ecija
Poblacion Central,General Tinio,Nueva Ecija
Pulong Matong,General Tinio,Nueva Ecija
Sampaguita,General Tinio,Nueva Ecija
Palale,General Tinio,Nueva Ecija
Agcano,Guimba,Nueva Ecija
Ayos Lomboy,Guimba,Nueva Ecija
Bacayao,Guimba,Nueva Ecija
Bagong Barrio,Guimba,Nueva Ecija
Balbalino,Guimba,Nueva Ecija
Balingog East,Guimba,Nueva Ecija
Balingog West,Guimba,Nueva Ecija
Banitan,Guimba,Nueva Ecija
Bantug,Guimba,Nueva Ecija
Bulakid,Guimba,Nueva Ecija
Caballero,Guimba,Nueva Ecija
Cabaruan,Guimba,Nueva Ecija
Caingin Tabing Ilog,Guimba,Nueva Ecija
Calem,Guimba,Nueva Ecija
Camiing,Guimba,Nueva Ecija
Cardinal,Guimba,Nueva Ecija
Casongsong,Guimba,Nueva Ecija
Catimon,Guimba,Nueva Ecija
Cavite,Guimba,Nueva Ecija
Cawayan Bugtong,Guimba,Nueva Ecija
Consuelo,Guimba,Nueva Ecija
Culong,Guimba,Nueva Ecija
Escano,Guimba,Nueva Ecija
Faigal,Guimba,Nueva Ecija
Galvan,Guimba,Nueva Ecija
Guiset,Guimba,Nueva Ecija
Lamorito,Guimba,Nueva Ecija
Lennec,Guimba,Nueva Ecija
Macamias,Guimba,Nueva Ecija
Macapabellag,Guimba,Nueva Ecija
Macatcatuit,Guimba,Nueva Ecija
Manacsac,Guimba,Nueva Ecija
Manggang Marikit,Guimba,Nueva Ecija
Maturanoc,Guimba,Nueva Ecija
Maybubon,Guimba,Nueva Ecija
Naglabrahan,Guimba,Nueva Ecija
Nagpandayan,Guimba,Nueva Ecija
Narvacan I,Guimba,Nueva Ecija
Narvacan II,Guimba,Nueva Ecija
Pacac,Guimba,Nueva Ecija
Partida I,Guimba,Nueva Ecija
Partida II,Guimba,Nueva Ecija
Pasong Inchic,Guimba,Nueva Ecija
Saint John District,Guimba,Nueva Ecija
San Agustin,Guimba,Nueva Ecija
San Andres,Guimba,Nueva Ecija
San Bernardino,Guimba,Nueva Ecija
San Marcelino,Guimba,Nueva Ecija
San Miguel,Guimba,Nueva Ecija
San Rafael,Guimba,Nueva Ecija
San Roque,Guimba,Nueva Ecija
Santa Ana,Guimba,Nueva Ecija
Santa Cruz,Guimba,Nueva Ecija
Santa Lucia,Guimba,Nueva Ecija
Santa Veronica District,Guimba,Nueva Ecija
Santo Cristo District,Guimba,Nueva Ecija
Saranay District,Guimba,Nueva Ecija
Sinulatan,Guimba,Nueva Ecija
Subol,Guimba,Nueva Ecija
Tampac I,Guimba,Nueva Ecija
Tampac II & III,Guimba,Nueva Ecija
Triala,Guimba,Nueva Ecija
Yuson,Guimba,Nueva Ecija
Bunol,Guimba,Nueva Ecija
Calabasa,Jaen,Nueva Ecija
Dampulan,Jaen,Nueva Ecija
Hilera,Jaen,Nueva Ecija
Imbunia,Jaen,Nueva Ecija
Imelda Pob.,Jaen,Nueva Ecija
Lambakin,Jaen,Nueva Ecija
Langla,Jaen,Nueva Ecija
Magsalisi,Jaen,Nueva Ecija
Malabon-Kaingin,Jaen,Nueva Ecija
Marawa,Jaen,Nueva Ecija
Don Mariano Marcos,Jaen,Nueva Ecija
San Josef,Jaen,Nueva Ecija
Niyugan,Jaen,Nueva Ecija
Pamacpacan,Jaen,Nueva Ecija
Pakol,Jaen,Nueva Ecija
Pinanggaan,Jaen,Nueva Ecija
Ulanin-Pitak,Jaen,Nueva Ecija
Putlod,Jaen,Nueva Ecija
Ocampo-Rivera District,Jaen,Nueva Ecija
San Jose,Jaen,Nueva Ecija
San Pablo,Jaen,Nueva Ecija
San Roque,Jaen,Nueva Ecija
San Vicente,Jaen,Nueva Ecija
Santa Rita,Jaen,Nueva Ecija
Santo Tomas North,Jaen,Nueva Ecija
Santo Tomas South,Jaen,Nueva Ecija
Sapang,Jaen,Nueva Ecija
Barangay I,Laur,Nueva Ecija
Barangay II,Laur,Nueva Ecija
Barangay III,Laur,Nueva Ecija
Barangay IV,Laur,Nueva Ecija
Betania,Laur,Nueva Ecija
Canantong,Laur,Nueva Ecija
Nauzon,Laur,Nueva Ecija
Pangarulong,Laur,Nueva Ecija
Pinagbayanan,Laur,Nueva Ecija
Sagana,Laur,Nueva Ecija
San Fernando,Laur,Nueva Ecija
San Isidro,Laur,Nueva Ecija
San Josef,Laur,Nueva Ecija
San Juan,Laur,Nueva Ecija
San Vicente,Laur,Nueva Ecija
Siclong,Laur,Nueva Ecija
San Felipe,Laur,Nueva Ecija
Linao,Licab,Nueva Ecija
Poblacion Norte,Licab,Nueva Ecija
Poblacion Sur,Licab,Nueva Ecija
San Casimiro,Licab,Nueva Ecija
San Cristobal,Licab,Nueva Ecija
San Jose,Licab,Nueva Ecija
San Juan,Licab,Nueva Ecija
Santa Maria,Licab,Nueva Ecija
Tabing Ilog,Licab,Nueva Ecija
Villarosa,Licab,Nueva Ecija
Aquino,Licab,Nueva Ecija
A. Bonifacio,Llanera,Nueva Ecija
Caridad Norte,Llanera,Nueva Ecija
Caridad Sur,Llanera,Nueva Ecija
Casile,Llanera,Nueva Ecija
Florida Blanca,Llanera,Nueva Ecija
General Luna,Llanera,Nueva Ecija
General Ricarte,Llanera,Nueva Ecija
Gomez,Llanera,Nueva Ecija
Inanama,Llanera,Nueva Ecija
Ligaya,Llanera,Nueva Ecija
Mabini,Llanera,Nueva Ecija
Murcon,Llanera,Nueva Ecija
Plaridel,Llanera,Nueva Ecija
Bagumbayan,Llanera,Nueva Ecija
San Felipe,Llanera,Nueva Ecija
San Francisco,Llanera,Nueva Ecija
San Nicolas,Llanera,Nueva Ecija
San Vicente,Llanera,Nueva Ecija
Santa Barbara,Llanera,Nueva Ecija
Victoria,Llanera,Nueva Ecija
Villa Viniegas,Llanera,Nueva Ecija
Bosque,Llanera,Nueva Ecija
Agupalo Este,Lupao,Nueva Ecija
Agupalo Weste,Lupao,Nueva Ecija
Alalay Chica,Lupao,Nueva Ecija
Alalay Grande,Lupao,Nueva Ecija
J. U. Tienzo,Lupao,Nueva Ecija
Bagong Flores,Lupao,Nueva Ecija
Balbalungao,Lupao,Nueva Ecija
Burgos,Lupao,Nueva Ecija
Cordero,Lupao,Nueva Ecija
Mapangpang,Lupao,Nueva Ecija
Namulandayan,Lupao,Nueva Ecija
Parista,Lupao,Nueva Ecija
Poblacion East,Lupao,Nueva Ecija
Poblacion North,Lupao,Nueva Ecija
Poblacion South,Lupao,Nueva Ecija
Poblacion West,Lupao,Nueva Ecija
Salvacion I,Lupao,Nueva Ecija
Salvacion II,Lupao,Nueva Ecija
San Antonio Este,Lupao,Nueva Ecija
San Antonio Weste,Lupao,Nueva Ecija
San Isidro,Lupao,Nueva Ecija
San Pedro,Lupao,Nueva Ecija
San Roque,Lupao,Nueva Ecija
Santo Domingo,Lupao,Nueva Ecija
Bagong Sikat,Science City of Muñoz,Nueva Ecija
Balante,Science City of Muñoz,Nueva Ecija
Bantug,Science City of Muñoz,Nueva Ecija
Bical,Science City of Muñoz,Nueva Ecija
Cabisuculan,Science City of Muñoz,Nueva Ecija
Calabalabaan,Science City of Muñoz,Nueva Ecija
Calisitan,Science City of Muñoz,Nueva Ecija
Catalanacan,Science City of Muñoz,Nueva Ecija
Curva,Science City of Muñoz,Nueva Ecija
Franza,Science City of Muñoz,Nueva Ecija
Gabaldon,Science City of Muñoz,Nueva Ecija
Labney,Science City of Muñoz,Nueva Ecija
Licaong,Science City of Muñoz,Nueva Ecija
Linglingay,Science City of Muñoz,Nueva Ecija
Mangandingay,Science City of Muñoz,Nueva Ecija
Magtanggol,Science City of Muñoz,Nueva Ecija
Maligaya,Science City of Muñoz,Nueva Ecija
Mapangpang,Science City of Muñoz,Nueva Ecija
Maragol,Science City of Muñoz,Nueva Ecija
Matingkis,Science City of Muñoz,Nueva Ecija
Naglabrahan,Science City of Muñoz,Nueva Ecija
Palusapis,Science City of Muñoz,Nueva Ecija
Pandalla,Science City of Muñoz,Nueva Ecija
Poblacion East,Science City of Muñoz,Nueva Ecija
Poblacion North,Science City of Muñoz,Nueva Ecija
Poblacion South,Science City of Muñoz,Nueva Ecija
Poblacion West,Science City of Muñoz,Nueva Ecija
Rang-ayan,Science City of Muñoz,Nueva Ecija
Rizal,Science City of Muñoz,Nueva Ecija
San Andres,Science City of Muñoz,Nueva Ecija
San Antonio,Science City of Muñoz,Nueva Ecija
San Felipe,Science City of Muñoz,Nueva Ecija
Sapang Cawayan,Science City of Muñoz,Nueva Ecija
Villa Isla,Science City of Muñoz,Nueva Ecija
Villa Nati,Science City of Muñoz,Nueva Ecija
Villa Santos,Science City of Muñoz,Nueva Ecija
Villa Cuizon,Science City of Muñoz,Nueva Ecija
Alemania,Nampicuan,Nueva Ecija
Ambasador Alzate Village,Nampicuan,Nueva Ecija
Cabaducan East,Nampicuan,Nueva Ecija
Cabaducan West,Nampicuan,Nueva Ecija
Cabawangan,Nampicuan,Nueva Ecija
East Central Poblacion,Nampicuan,Nueva Ecija
Edy,Nampicuan,Nueva Ecija
Maeling,Nampicuan,Nueva Ecija
Mayantoc,Nampicuan,Nueva Ecija
Medico,Nampicuan,Nueva Ecija
Monic,Nampicuan,Nueva Ecija
North Poblacion,Nampicuan,Nueva Ecija
Northwest Poblacion,Nampicuan,Nueva Ecija
Estacion,Nampicuan,Nueva Ecija
West Poblacion,Nampicuan,Nueva Ecija
Recuerdo,Nampicuan,Nueva Ecija
South Central Poblacion,Nampicuan,Nueva Ecija
Southeast Poblacion,Nampicuan,Nueva Ecija
Southwest Poblacion,Nampicuan,Nueva Ecija
Tony,Nampicuan,Nueva Ecija
West Central Poblacion,Nampicuan,Nueva Ecija
Aulo,City of Palayan,Nueva Ecija
Bo. Militar,City of Palayan,Nueva Ecija
Ganaderia,City of Palayan,Nueva Ecija
Maligaya,City of Palayan,Nueva Ecija
Manacnac,City of Palayan,Nueva Ecija
Mapait,City of Palayan,Nueva Ecija
Marcos Village,City of Palayan,Nueva Ecija
Malate,City of Palayan,Nueva Ecija
Sapang Buho,City of Palayan,Nueva Ecija
Singalat,City of Palayan,Nueva Ecija
Atate,City of Palayan,Nueva Ecija
Caballero,City of Palayan,Nueva Ecija
Caimito,City of Palayan,Nueva Ecija
Doña Josefa,City of Palayan,Nueva Ecija
Imelda Valley,City of Palayan,Nueva Ecija
Langka,City of Palayan,Nueva Ecija
Santolan,City of Palayan,Nueva Ecija
Popolon Pagas,City of Palayan,Nueva Ecija
Bagong Buhay,City of Palayan,Nueva Ecija
Cadaclan,Pantabangan,Nueva Ecija
Cambitala,Pantabangan,Nueva Ecija
Conversion,Pantabangan,Nueva Ecija
Ganduz,Pantabangan,Nueva Ecija
Liberty,Pantabangan,Nueva Ecija
Malbang,Pantabangan,Nueva Ecija
Marikit,Pantabangan,Nueva Ecija
Napon-Napon,Pantabangan,Nueva Ecija
Poblacion East,Pantabangan,Nueva Ecija
Poblacion West,Pantabangan,Nueva Ecija
Sampaloc,Pantabangan,Nueva Ecija
San Juan,Pantabangan,Nueva Ecija
Villarica,Pantabangan,Nueva Ecija
Fatima,Pantabangan,Nueva Ecija
Callos,Peñaranda,Nueva Ecija
Las Piñas,Peñaranda,Nueva Ecija
Poblacion I,Peñaranda,Nueva Ecija
Poblacion II,Peñaranda,Nueva Ecija
Poblacion III,Peñaranda,Nueva Ecija
Poblacion IV,Peñaranda,Nueva Ecija
Santo Tomas,Peñaranda,Nueva Ecija
Sinasajan,Peñaranda,Nueva Ecija
San Josef,Peñaranda,Nueva Ecija
San Mariano,Peñaranda,Nueva Ecija
Bertese,Quezon,Nueva Ecija
Doña Lucia,Quezon,Nueva Ecija
Dulong Bayan,Quezon,Nueva Ecija
Ilog Baliwag,Quezon,Nueva Ecija
Barangay I,Quezon,Nueva Ecija
Barangay II,Quezon,Nueva Ecija
Pulong Bahay,Quezon,Nueva Ecija
San Alejandro,Quezon,Nueva Ecija
San Andres I,Quezon,Nueva Ecija
San Andres II,Quezon,Nueva Ecija
San Manuel,Quezon,Nueva Ecija
Santa Clara,Quezon,Nueva Ecija
Santa Rita,Quezon,Nueva Ecija
Santo Cristo,Quezon,Nueva Ecija
Santo Tomas Feria,Quezon,Nueva Ecija
San Miguel,Quezon,Nueva Ecija
Agbannawag,Rizal,Nueva Ecija
Bicos,Rizal,Nueva Ecija
Cabucbucan,Rizal,Nueva Ecija
Calaocan District,Rizal,Nueva Ecija
Canaan East,Rizal,Nueva Ecija
Canaan West,Rizal,Nueva Ecija
Casilagan,Rizal,Nueva Ecija
Aglipay,Rizal,Nueva Ecija
Del Pilar,Rizal,Nueva Ecija
Estrella,Rizal,Nueva Ecija
General Luna,Rizal,Nueva Ecija
Macapsing,Rizal,Nueva Ecija
Maligaya,Rizal,Nueva Ecija
Paco Roman,Rizal,Nueva Ecija
Pag-asa,Rizal,Nueva Ecija
Poblacion Central,Rizal,Nueva Ecija
Poblacion East,Rizal,Nueva Ecija
Poblacion Norte,Rizal,Nueva Ecija
Poblacion Sur,Rizal,Nueva Ecija
Poblacion West,Rizal,Nueva Ecija
Portal,Rizal,Nueva Ecija
San Esteban,Rizal,Nueva Ecija
Santa Monica,Rizal,Nueva Ecija
Villa Labrador,Rizal,Nueva Ecija
Villa Paraiso,Rizal,Nueva Ecija
San Gregorio,Rizal,Nueva Ecija
Buliran,San Antonio,Nueva Ecija
Cama Juan,San Antonio,Nueva Ecija
Julo,San Antonio,Nueva Ecija
Lawang Kupang,San Antonio,Nueva Ecija
Luyos,San Antonio,Nueva Ecija
Maugat,San Antonio,Nueva Ecija
Panabingan,San Antonio,Nueva Ecija
Papaya,San Antonio,Nueva Ecija
Poblacion,San Antonio,Nueva Ecija
San Francisco,San Antonio,Nueva Ecija
San Jose,San Antonio,Nueva Ecija
San Mariano,San Antonio,Nueva Ecija
Santa Cruz,San Antonio,Nueva Ecija
Santo Cristo,San Antonio,Nueva Ecija
Santa Barbara,San Antonio,Nueva Ecija
Tikiw,San Antonio,Nueva Ecija
Alua,San Isidro,Nueva Ecija
Calaba,San Isidro,Nueva Ecija
Malapit,San Isidro,Nueva Ecija
Mangga,San Isidro,Nueva Ecija
Poblacion,San Isidro,Nueva Ecija
Pulo,San Isidro,Nueva Ecija
San Roque,San Isidro,Nueva Ecija
Sto. Cristo,San Isidro,Nueva Ecija
Tabon,San Isidro,Nueva Ecija
A. Pascual,San Jose City,Nueva Ecija
Abar Ist,San Jose City,Nueva Ecija
Abar 2nd,San Jose City,Nueva Ecija
Bagong Sikat,San Jose City,Nueva Ecija
Caanawan,San Jose City,Nueva Ecija
Calaocan,San Jose City,Nueva Ecija
Camanacsacan,San Jose City,Nueva Ecija
Culaylay,San Jose City,Nueva Ecija
Dizol,San Jose City,Nueva Ecija
Kaliwanagan,San Jose City,Nueva Ecija
Kita-Kita,San Jose City,Nueva Ecija
Malasin,San Jose City,Nueva Ecija
Manicla,San Jose City,Nueva Ecija
Palestina,San Jose City,Nueva Ecija
Parang Mangga,San Jose City,Nueva Ecija
Villa Joson,San Jose City,Nueva Ecija
Pinili,San Jose City,Nueva Ecija
"Rafael Rueda, Sr. Pob.",San Jose City,Nueva Ecija
Ferdinand E. Marcos Pob.,San Jose City,Nueva Ecija
Canuto Ramos Pob.,San Jose City,Nueva Ecija
Raymundo Eugenio Pob.,San Jose City,Nueva Ecija
Crisanto Sanchez Pob.,San Jose City,Nueva Ecija
Porais,San Jose City,Nueva Ecija
San Agustin,San Jose City,Nueva Ecija
San Juan,San Jose City,Nueva Ecija
San Mauricio,San Jose City,Nueva Ecija
Santo Niño 1st,San Jose City,Nueva Ecija
Santo Niño 2nd,San Jose City,Nueva Ecija
Santo Tomas,San Jose City,Nueva Ecija
Sibut,San Jose City,Nueva Ecija
Sinipit Bubon,San Jose City,Nueva Ecija
Santo Niño 3rd,San Jose City,Nueva Ecija
Tabulac,San Jose City,Nueva Ecija
Tayabo,San Jose City,Nueva Ecija
Tondod,San Jose City,Nueva Ecija
Tulat,San Jose City,Nueva Ecija
Villa Floresca,San Jose City,Nueva Ecija
Villa Marina,San Jose City,Nueva Ecija
Bonifacio District,San Leonardo,Nueva Ecija
Burgos District,San Leonardo,Nueva Ecija
Castellano,San Leonardo,Nueva Ecija
Diversion,San Leonardo,Nueva Ecija
Magpapalayoc,San Leonardo,Nueva Ecija
Mallorca,San Leonardo,Nueva Ecija
Mambangnan,San Leonardo,Nueva Ecija
Nieves,San Leonardo,Nueva Ecija
San Bartolome,San Leonardo,Nueva Ecija
Rizal District,San Leonardo,Nueva Ecija
San Anton,San Leonardo,Nueva Ecija
San Roque,San Leonardo,Nueva Ecija
Tabuating,San Leonardo,Nueva Ecija
Tagumpay,San Leonardo,Nueva Ecija
Tambo Adorable,San Leonardo,Nueva Ecija
Cojuangco,Santa Rosa,Nueva Ecija
La Fuente,Santa Rosa,Nueva Ecija
Liwayway,Santa Rosa,Nueva Ecija
Malacañang,Santa Rosa,Nueva Ecija
Maliolio,Santa Rosa,Nueva Ecija
Mapalad,Santa Rosa,Nueva Ecija
Rizal,Santa Rosa,Nueva Ecija
Rajal Centro,Santa Rosa,Nueva Ecija
Rajal Norte,Santa Rosa,Nueva Ecija
Rajal Sur,Santa Rosa,Nueva Ecija
San Gregorio,Santa Rosa,Nueva Ecija
San Mariano,Santa Rosa,Nueva Ecija
San Pedro,Santa Rosa,Nueva Ecija
Santo Rosario,Santa Rosa,Nueva Ecija
Soledad,Santa Rosa,Nueva Ecija
Valenzuela,Santa Rosa,Nueva Ecija
Zamora,Santa Rosa,Nueva Ecija
Aguinaldo,Santa Rosa,Nueva Ecija
Berang,Santa Rosa,Nueva Ecija
Burgos,Santa Rosa,Nueva Ecija
Del Pilar,Santa Rosa,Nueva Ecija
Gomez,Santa Rosa,Nueva Ecija
Inspector,Santa Rosa,Nueva Ecija
Isla,Santa Rosa,Nueva Ecija
Lourdes,Santa Rosa,Nueva Ecija
Luna,Santa Rosa,Nueva Ecija
Mabini,Santa Rosa,Nueva Ecija
San Isidro,Santa Rosa,Nueva Ecija
San Josep,Santa Rosa,Nueva Ecija
Santa Teresita,Santa Rosa,Nueva Ecija
Sapsap,Santa Rosa,Nueva Ecija
Tagpos,Santa Rosa,Nueva Ecija
Tramo,Santa Rosa,Nueva Ecija
Baloc,Santo Domingo,Nueva Ecija
Buasao,Santo Domingo,Nueva Ecija
Burgos,Santo Domingo,Nueva Ecija
Cabugao,Santo Domingo,Nueva Ecija
Casulucan,Santo Domingo,Nueva Ecija
Comitang,Santo Domingo,Nueva Ecija
Concepcion,Santo Domingo,Nueva Ecija
Dolores,Santo Domingo,Nueva Ecija
General Luna,Santo Domingo,Nueva Ecija
Hulo,Santo Domingo,Nueva Ecija
Mabini,Santo Domingo,Nueva Ecija
Malasin,Santo Domingo,Nueva Ecija
Malayantoc,Santo Domingo,Nueva Ecija
Mambarao,Santo Domingo,Nueva Ecija
Poblacion,Santo Domingo,Nueva Ecija
Malaya,Santo Domingo,Nueva Ecija
Pulong Buli,Santo Domingo,Nueva Ecija
Sagaba,Santo Domingo,Nueva Ecija
San Agustin,Santo Domingo,Nueva Ecija
San Fabian,Santo Domingo,Nueva Ecija
San Francisco,Santo Domingo,Nueva Ecija
San Pascual,Santo Domingo,Nueva Ecija
Santa Rita,Santo Domingo,Nueva Ecija
Santo Rosario,Santo Domingo,Nueva Ecija
Andal Alino,Talavera,Nueva Ecija
Bagong Sikat,Talavera,Nueva Ecija
Bagong Silang,Talavera,Nueva Ecija
Bakal I,Talavera,Nueva Ecija
Bakal II,Talavera,Nueva Ecija
Bakal III,Talavera,Nueva Ecija
Baluga,Talavera,Nueva Ecija
Bantug,Talavera,Nueva Ecija
Bantug Hacienda,Talavera,Nueva Ecija
Bantug Hamog,Talavera,Nueva Ecija
Bugtong na Buli,Talavera,Nueva Ecija
Bulac,Talavera,Nueva Ecija
Burnay,Talavera,Nueva Ecija
Calipahan,Talavera,Nueva Ecija
Campos,Talavera,Nueva Ecija
Casulucan Este,Talavera,Nueva Ecija
Collado,Talavera,Nueva Ecija
Dimasalang Norte,Talavera,Nueva Ecija
Dimasalang Sur,Talavera,Nueva Ecija
Dinarayat,Talavera,Nueva Ecija
Esguerra District,Talavera,Nueva Ecija
Gulod,Talavera,Nueva Ecija
Homestead I,Talavera,Nueva Ecija
Homestead II,Talavera,Nueva Ecija
Cabubulaonan,Talavera,Nueva Ecija
Caaniplahan,Talavera,Nueva Ecija
Caputican,Talavera,Nueva Ecija
Kinalanguyan,Talavera,Nueva Ecija
La Torre,Talavera,Nueva Ecija
Lomboy,Talavera,Nueva Ecija
Mabuhay,Talavera,Nueva Ecija
Maestrang Kikay,Talavera,Nueva Ecija
Mamandil,Talavera,Nueva Ecija
Marcos District,Talavera,Nueva Ecija
Purok Matias,Talavera,Nueva Ecija
Matingkis,Talavera,Nueva Ecija
Minabuyoc,Talavera,Nueva Ecija
Pag-asa,Talavera,Nueva Ecija
Paludpod,Talavera,Nueva Ecija
Pantoc Bulac,Talavera,Nueva Ecija
Pinagpanaan,Talavera,Nueva Ecija
Poblacion Sur,Talavera,Nueva Ecija
Pula,Talavera,Nueva Ecija
Pulong San Miguel,Talavera,Nueva Ecija
Sampaloc,Talavera,Nueva Ecija
San Miguel na Munti,Talavera,Nueva Ecija
San Pascual,Talavera,Nueva Ecija
San Ricardo,Talavera,Nueva Ecija
Sibul,Talavera,Nueva Ecija
Sicsican Matanda,Talavera,Nueva Ecija
Tabacao,Talavera,Nueva Ecija
Tagaytay,Talavera,Nueva Ecija
Valle,Talavera,Nueva Ecija
Alula,Talugtug,Nueva Ecija
Baybayabas,Talugtug,Nueva Ecija
Buted,Talugtug,Nueva Ecija
Cabiangan,Talugtug,Nueva Ecija
Calisitan,Talugtug,Nueva Ecija
Cinense,Talugtug,Nueva Ecija
Culiat,Talugtug,Nueva Ecija
Maasin,Talugtug,Nueva Ecija
Magsaysay,Talugtug,Nueva Ecija
Mayamot I,Talugtug,Nueva Ecija
Mayamot II,Talugtug,Nueva Ecija
Nangabulan,Talugtug,Nueva Ecija
Osmeña,Talugtug,Nueva Ecija
Pangit,Talugtug,Nueva Ecija
Patola,Talugtug,Nueva Ecija
Quezon,Talugtug,Nueva Ecija
Quirino,Talugtug,Nueva Ecija
Roxas,Talugtug,Nueva Ecija
Saguing,Talugtug,Nueva Ecija
Sampaloc,Talugtug,Nueva Ecija
Santa Catalina,Talugtug,Nueva Ecija
Santo Domingo,Talugtug,Nueva Ecija
Saringaya,Talugtug,Nueva Ecija
Saverona,Talugtug,Nueva Ecija
Tandoc,Talugtug,Nueva Ecija
Tibag,Talugtug,Nueva Ecija
Villa Rosario,Talugtug,Nueva Ecija
Villa Boado,Talugtug,Nueva Ecija
Batitang,Zaragoza,Nueva Ecija
Carmen,Zaragoza,Nueva Ecija
Concepcion,Zaragoza,Nueva Ecija
Del Pilar,Zaragoza,Nueva Ecija
General Luna,Zaragoza,Nueva Ecija
H. Romero,Zaragoza,Nueva Ecija
Macarse,Zaragoza,Nueva Ecija
Manaul,Zaragoza,Nueva Ecija
Mayamot,Zaragoza,Nueva Ecija
Pantoc,Zaragoza,Nueva Ecija
San Vicente,Zaragoza,Nueva Ecija
San Isidro,Zaragoza,Nueva Ecija
San Rafael,Zaragoza,Nueva Ecija
Santa Cruz,Zaragoza,Nueva Ecija
Santa Lucia Old,Zaragoza,Nueva Ecija
Santa Lucia Young,Zaragoza,Nueva Ecija
Santo Rosario Old,Zaragoza,Nueva Ecija
Santo Rosario Young,Zaragoza,Nueva Ecija
Valeriana,Zaragoza,Nueva Ecija
Barangay I,Baler,Aurora
Barangay II,Baler,Aurora
Barangay III,Baler,Aurora
Barangay IV,Baler,Aurora
Barangay V,Baler,Aurora
Buhangin,Baler,Aurora
Calabuanan,Baler,Aurora
Obligacion,Baler,Aurora
Pingit,Baler,Aurora
Reserva,Baler,Aurora
Sabang,Baler,Aurora
Suclayin,Baler,Aurora
Zabali,Baler,Aurora
Barangay 1,Casiguran,Aurora
Barangay 2,Casiguran,Aurora
Barangay 3,Casiguran,Aurora
Barangay 4,Casiguran,Aurora
Barangay 5,Casiguran,Aurora
Barangay 6,Casiguran,Aurora
Barangay 7,Casiguran,Aurora
Barangay 8,Casiguran,Aurora
Calabgan,Casiguran,Aurora
Calangcuasan,Casiguran,Aurora
Calantas,Casiguran,Aurora
Culat,Casiguran,Aurora
Dibet,Casiguran,Aurora
Esperanza,Casiguran,Aurora
Lual,Casiguran,Aurora
Marikit,Casiguran,Aurora
Tabas,Casiguran,Aurora
Tinib,Casiguran,Aurora
Bianoan,Casiguran,Aurora
Cozo,Casiguran,Aurora
Dibacong,Casiguran,Aurora
Ditinagyan,Casiguran,Aurora
Esteves,Casiguran,Aurora
San Ildefonso,Casiguran,Aurora
Diagyan,Dilasag,Aurora
Dicabasan,Dilasag,Aurora
Dilaguidi,Dilasag,Aurora
Dimaseset,Dilasag,Aurora
Diniog,Dilasag,Aurora
Lawang,Dilasag,Aurora
Maligaya,Dilasag,Aurora
Manggitahan,Dilasag,Aurora
Masagana,Dilasag,Aurora
Ura,Dilasag,Aurora
Esperanza,Dilasag,Aurora
Abuleg,Dinalungan,Aurora
Zone I,Dinalungan,Aurora
Zone II,Dinalungan,Aurora
Nipoo,Dinalungan,Aurora
Dibaraybay,Dinalungan,Aurora
Ditawini,Dinalungan,Aurora
Mapalad,Dinalungan,Aurora
Paleg,Dinalungan,Aurora
Simbahan,Dinalungan,Aurora
Aplaya,Dingalan,Aurora
Butas Na Bato,Dingalan,Aurora
Cabog,Dingalan,Aurora
Caragsacan,Dingalan,Aurora
Davildavilan,Dingalan,Aurora
Dikapanikian,Dingalan,Aurora
Ibona,Dingalan,Aurora
Paltic,Dingalan,Aurora
Poblacion,Dingalan,Aurora
Tanawan,Dingalan,Aurora
Umiray,Dingalan,Aurora
Bayabas,Dipaculao,Aurora
Buenavista,Dipaculao,Aurora
Borlongan,Dipaculao,Aurora
Calaocan,Dipaculao,Aurora
Dianed,Dipaculao,Aurora
Diarabasin,Dipaculao,Aurora
Dibutunan,Dipaculao,Aurora
Dimabuno,Dipaculao,Aurora
Dinadiawan,Dipaculao,Aurora
Ditale,Dipaculao,Aurora
Gupa,Dipaculao,Aurora
Ipil,Dipaculao,Aurora
Laboy,Dipaculao,Aurora
Lipit,Dipaculao,Aurora
Lobbot,Dipaculao,Aurora
Maligaya,Dipaculao,Aurora
Mijares,Dipaculao,Aurora
Mucdol,Dipaculao,Aurora
North Poblacion,Dipaculao,Aurora
Puangi,Dipaculao,Aurora
Salay,Dipaculao,Aurora
Sapangkawayan,Dipaculao,Aurora
South Poblacion,Dipaculao,Aurora
Toytoyan,Dipaculao,Aurora
Diamanen,Dipaculao,Aurora
Alcala,Maria Aurora,Aurora
Bagtu,Maria Aurora,Aurora
Bangco,Maria Aurora,Aurora
Bannawag,Maria Aurora,Aurora
Barangay I,Maria Aurora,Aurora
Barangay II,Maria Aurora,Aurora
Barangay III,Maria Aurora,Aurora
Barangay IV,Maria Aurora,Aurora
Baubo,Maria Aurora,Aurora
Bayanihan,Maria Aurora,Aurora
Bazal,Maria Aurora,Aurora
Cabituculan East,Maria Aurora,Aurora
Cabituculan West,Maria Aurora,Aurora
Debucao,Maria Aurora,Aurora
Decoliat,Maria Aurora,Aurora
Detailen,Maria Aurora,Aurora
Diaat,Maria Aurora,Aurora
Dialatman,Maria Aurora,Aurora
Diaman,Maria Aurora,Aurora
Dianawan,Maria Aurora,Aurora
Dikildit,Maria Aurora,Aurora
Dimanpudso,Maria Aurora,Aurora
Diome,Maria Aurora,Aurora
Estonilo,Maria Aurora,Aurora
Florida,Maria Aurora,Aurora
Galintuja,Maria Aurora,Aurora
Cadayacan,Maria Aurora,Aurora
Malasin,Maria Aurora,Aurora
Ponglo,Maria Aurora,Aurora
Quirino,Maria Aurora,Aurora
Ramada,Maria Aurora,Aurora
San Joaquin,Maria Aurora,Aurora
San Jose,Maria Aurora,Aurora
San Leonardo,Maria Aurora,Aurora
Santa Lucia,Maria Aurora,Aurora
Santo Tomas,Maria Aurora,Aurora
Suguit,Maria Aurora,Aurora
Villa Aurora,Maria Aurora,Aurora
Wenceslao,Maria Aurora,Aurora
San Juan,Maria Aurora,Aurora
Bacong,San Luis,Aurora
Barangay I,San Luis,Aurora
Barangay II,San Luis,Aurora
Barangay III,San Luis,Aurora
Barangay IV,San Luis,Aurora
Dibalo,San Luis,Aurora
Dibayabay,San Luis,Aurora
Dibut,San Luis,Aurora
Dikapinisan,San Luis,Aurora
Dimanayat,San Luis,Aurora
Diteki,San Luis,Aurora
Ditumabo,San Luis,Aurora
L. Pimentel,San Luis,Aurora
Nonong Senior,San Luis,Aurora
Real,San Luis,Aurora
San Isidro,San Luis,Aurora
San Jose,San Luis,Aurora
Zarah,San Luis,Aurora`;

    function parseCsvLine(line) {
        const values = [];
        let current = '';
        let insideQuotes = false;

        for (let i = 0; i < line.length; i++) {
            const char = line[i];
            if (char === '"') {
                if (insideQuotes && line[i + 1] === '"') {
                    current += '"';
                    i++;
                } else {
                    insideQuotes = !insideQuotes;
                }
            } else if (char === ',' && !insideQuotes) {
                values.push(current);
                current = '';
            } else {
                current += char;
            }
        }

        values.push(current);
        return values;
    }

    function parseLocationData(csv) {
        const data = {};
        const lines = csv.split(/\r?\n/).filter(line => line.trim().length > 0);

        for (let i = 1; i < lines.length; i++) {
            const [barangay, municipality, province] = parseCsvLine(lines[i]);
            if (!province || !municipality || !barangay) {
                continue;
            }

            if (!data[province]) {
                data[province] = {};
            }
            if (!data[province][municipality]) {
                data[province][municipality] = [];
            }
            if (!data[province][municipality].includes(barangay)) {
                data[province][municipality].push(barangay);
            }
        }

        for (const province of Object.keys(data)) {
            for (const municipality of Object.keys(data[province])) {
                data[province][municipality].sort((a, b) => a.localeCompare(b, 'en', { sensitivity: 'base' }));
            }
        }

        return data;
    }

    const locationData = parseLocationData(locationCsv);

    function populateSelect(selectElement, options, placeholder) {
        selectElement.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = placeholder;
        selectElement.appendChild(defaultOption);

        options.forEach(option => {
            const optionItem = document.createElement('option');
            optionItem.value = option;
            optionItem.textContent = option;
            selectElement.appendChild(optionItem);
        });
    }

    function updateMunicipalities(provinceSelect, municipalitySelect, barangaySelect) {
        if (!provinceSelect.value) {
            populateSelect(municipalitySelect, [], 'Select Municipality');
            municipalitySelect.disabled = true;
            populateSelect(barangaySelect, [], 'Select Barangay');
            barangaySelect.disabled = true;
            return;
        }

        const municipalities = Object.keys(locationData[provinceSelect.value] || {});
        populateSelect(municipalitySelect, municipalities, 'Select Municipality');
        municipalitySelect.disabled = false;
        populateSelect(barangaySelect, [], 'Select Barangay');
        barangaySelect.disabled = true;
    }

    function updateBarangays(provinceSelect, municipalitySelect, barangaySelect) {
        if (!provinceSelect.value || !municipalitySelect.value) {
            populateSelect(barangaySelect, [], 'Select Barangay');
            barangaySelect.disabled = true;
            return;
        }

        const barangays = locationData[provinceSelect.value]?.[municipalitySelect.value] || [];
        populateSelect(barangaySelect, barangays, 'Select Barangay');
        barangaySelect.disabled = false;
    }

    function setHiddenAddress(provinceSelect, municipalitySelect, barangaySelect, hiddenAddressInput) {
        if (!hiddenAddressInput) {
            return;
        }

        hiddenAddressInput.value = [barangaySelect.value, municipalitySelect.value, provinceSelect.value]
            .filter(Boolean)
            .join(', ');
    }

    if (addRecordButton && addRecordDialog) {
        addRecordButton.addEventListener('click', function () {
            addRecordDialog.showModal();
        });
    }

    const closeAddRecordModal = document.querySelector('.closeAddRecordModal');
    const addProvince = document.getElementById('province');
    const addMunicipality = document.getElementById('municipality');
    const addBarangay = document.getElementById('barangay');
    const addRecordAddress = document.getElementById('addRecordAddress');
    const addRecordForm = document.querySelector('.addRecordDialog form');

    if (closeAddRecordModal && addRecordDialog) {
        closeAddRecordModal.addEventListener('click', function () {
            addRecordDialog.close();
        });
    }

    if (addProvince && addMunicipality && addBarangay) {
        addProvince.addEventListener('change', function () {
            updateMunicipalities(addProvince, addMunicipality, addBarangay);
        });

        addMunicipality.addEventListener('change', function () {
            updateBarangays(addProvince, addMunicipality, addBarangay);
        });
    }

    if (addRecordForm) {
        addRecordForm.addEventListener('submit', function () {
            setHiddenAddress(addProvince, addMunicipality, addBarangay, addRecordAddress);
        });
    }

    // Edit Record Dialog

    document.querySelectorAll('.editButton').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const farmerName = this.getAttribute('data-farmerName');
            const province = this.getAttribute('data-province');
            const municipality = this.getAttribute('data-municipality');
            const barangay = this.getAttribute('data-barangay');
            const address = this.getAttribute('data-address');
            const program = this.getAttribute('data-program');
            const line = this.getAttribute('data-line');
            const causeOfDamage = this.getAttribute('data-causeOfDamage');
            const modeOfPayment = this.getAttribute('data-modeOfPayment');
            const remarks = this.getAttribute('data-remarks');
            const source = this.getAttribute('data-source');
            const transmittal_number = this.getAttribute('data-transmittal_number');
            const admin_transmittal_number = this.getAttribute('data-admin_transmittal_number');

            const editForm = document.querySelector('.editRecordform');
            if (editForm) {
                editForm.action = `/records/${id}`;
                console.log('Form action set to:', editForm.action);
            }

            if (farmerName) {
                const farmerNameInput = document.querySelector('.editRecordform input[name="farmerName"]');
                if (farmerNameInput) farmerNameInput.value = farmerName;
            }

            const editProvince = document.getElementById('editProvince');
            const editMunicipality = document.getElementById('editMunicipality');
            const editBarangay = document.getElementById('editBarangay');
            const editRecordAddress = document.getElementById('editRecordAddress');

            if (editProvince && editMunicipality && editBarangay) {
                if (province) {
                    editProvince.value = province;
                    updateMunicipalities(editProvince, editMunicipality, editBarangay);
                } else {
                    editProvince.value = '';
                    updateMunicipalities(editProvince, editMunicipality, editBarangay);
                }

                if (municipality) {
                    editMunicipality.value = municipality;
                    updateBarangays(editProvince, editMunicipality, editBarangay);
                } else {
                    editMunicipality.value = '';
                    updateBarangays(editProvince, editMunicipality, editBarangay);
                }

                if (barangay) {
                    editBarangay.value = barangay;
                }

                if (!province && address) {
                    const addressParts = address.split(',').map(part => part.trim());
                    editProvince.value = addressParts[2] || '';
                    updateMunicipalities(editProvince, editMunicipality, editBarangay);
                    editMunicipality.value = addressParts[1] || '';
                    updateBarangays(editProvince, editMunicipality, editBarangay);
                    editBarangay.value = addressParts[0] || '';
                }
            }

            const addressInput = document.querySelector('.editRecordform input[name="address"]');
            if (addressInput) {
                addressInput.value = address;
            }

            const programSelect = document.querySelector('.editRecordform select[name="program"]');
            if (programSelect) programSelect.value = program;

            const sourceSelect = document.querySelector('.editRecordform select[name="source"]');
            if (sourceSelect) sourceSelect.value = source;

            const lineSelect = document.querySelector('.editRecordform select[name="line"]');
            if (lineSelect) lineSelect.value = line;

            const causeInput = document.querySelector('.editRecordform input[name="causeOfDamage"]');
            if (causeInput) causeInput.value = causeOfDamage;

            const modeOfPaymentSelect = document.querySelector('.editRecordform select[name="modeOfPayment"]');
            if (modeOfPaymentSelect) modeOfPaymentSelect.value = modeOfPayment;

            const remarksInput = document.querySelector('.editRecordform input[name="remarks"]');
            if (remarksInput) remarksInput.value = remarks;

            const transmittalInput = document.querySelector('.editRecordform input[name="transmittal_number"]');
            if (transmittalInput) transmittalInput.value = transmittal_number || '';

            const adminTransmittalInput = document.querySelector('.editRecordform input[name="admin_transmittal_number"]');
            if (adminTransmittalInput) adminTransmittalInput.value = admin_transmittal_number || '';

            const clearAdminTransmittalCheckbox = document.querySelector('.editRecordform input[name="clear_admin_transmittal_number"]');
            if (clearAdminTransmittalCheckbox) {
                clearAdminTransmittalCheckbox.checked = false;
            }

            if (editRecordAddress) {
                setHiddenAddress(editProvince, editMunicipality, editBarangay, editRecordAddress);
            }

            const editDialog = document.querySelector('.editRecordDialog');
            if (editDialog) {
                editDialog.showModal();
            }
        });
    });

    const editProvinceInput = document.getElementById('editProvince');
    const editMunicipalityInput = document.getElementById('editMunicipality');
    const editBarangayInput = document.getElementById('editBarangay');
    const editRecordForm = document.querySelector('.editRecordform');
    const editRecordAddressInput = document.getElementById('editRecordAddress');

    if (editProvinceInput && editMunicipalityInput && editBarangayInput) {
        editProvinceInput.addEventListener('change', function () {
            updateMunicipalities(editProvinceInput, editMunicipalityInput, editBarangayInput);
        });

        editMunicipalityInput.addEventListener('change', function () {
            updateBarangays(editProvinceInput, editMunicipalityInput, editBarangayInput);
        });
    }

    if (editRecordForm) {
        editRecordForm.addEventListener('submit', function () {
            setHiddenAddress(editProvinceInput, editMunicipalityInput, editBarangayInput, editRecordAddressInput);
        });
    }

    // Table Search Button Functionality
    const tableSearchBtn = document.getElementById('table-search-btn');
    if (tableSearchBtn) {
        tableSearchBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Collect all filter values from table header
            const filterRow = document.querySelector('thead tr.filter-row');
            if (!filterRow) return;

            const params = new URLSearchParams();
            const inputs = filterRow.querySelectorAll('input, select');

            inputs.forEach(input => {
                const name = input.getAttribute('name');
                const value = input.value;
                if (name && value) {
                    params.append(name, value);
                }
            });

            // Build URL and search
            const searchUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.location.href = searchUrl;
        });
    }

    // Delete Record Dialog

    document.querySelectorAll('.deleteButton').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const farmerName = this.getAttribute('data-farmer-name');
            const deleteForm = document.querySelector('.deleteRecordForm');
            const message = document.querySelector('.deleteRecordMessage');
            const deleteDialog = document.querySelector('.deleteRecordDialog');

            if (deleteForm) {
                deleteForm.action = `/records/${id}`;
            }

            if (message) {
                message.textContent = `Record for ${farmerName} will be deleted.`;
            }

            if (deleteDialog) {
                deleteDialog.showModal();
            }
        });
    });

    // Cancel Delete Button
    const cancelDeleteButton = document.querySelector('.cancelDeleteRecord');
    const deleteRecordDialog = document.querySelector('.deleteRecordDialog');

    if (cancelDeleteButton && deleteRecordDialog) {
        cancelDeleteButton.addEventListener('click', function () {
            deleteRecordDialog.close();
        });
    }

    const closeEditRecordDialog = document.querySelector('.closeEditRecordDialog');
    const editRecordDialog = document.querySelector('.editRecordDialog');

    if (closeEditRecordDialog && editRecordDialog) {
        closeEditRecordDialog.addEventListener('click', function () {
            editRecordDialog.close();
        });
    }

    // Add form submit listener for debugging
    const editForm = document.querySelector('.editRecordform');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            console.log('Form submitted with action:', this.action);
            console.log('Form data:', new FormData(this));
        });
    }

    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    const recordCheckboxes = document.querySelectorAll('.record-checkbox');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            recordCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Bulk Delete Dialog
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkDeleteDialog = document.querySelector('.bulkDeleteDialog');
    const bulkDeleteList = document.querySelector('.bulk-delete-list');
    const confirmBulkDelete = document.getElementById('confirm-bulk-delete');
    const cancelBulkDelete = document.querySelector('.cancelBulkDelete');
    const bulkForm = document.getElementById('bulk-form');

    if (bulkDeleteBtn && bulkDeleteDialog) {
        bulkDeleteBtn.addEventListener('click', function () {
            const selectedCheckboxes = document.querySelectorAll('.record-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Please select at least one record to delete.');
                return;
            }

            // Clear previous list
            bulkDeleteList.innerHTML = '';

            // Add selected farmers to the list
            selectedCheckboxes.forEach(checkbox => {
                const farmerName = checkbox.dataset.farmerName || checkbox.closest('tr').cells[3].textContent;
                const li = document.createElement('li');
                li.textContent = farmerName;
                bulkDeleteList.appendChild(li);
            });

            bulkDeleteDialog.showModal();
        });
    }

    if (confirmBulkDelete && bulkForm) {
        confirmBulkDelete.addEventListener('click', function () {
            bulkDeleteDialog.close();
            bulkForm.submit();
        });
    }

    if (cancelBulkDelete && bulkDeleteDialog) {
        cancelBulkDelete.addEventListener('click', function () {
            bulkDeleteDialog.close();
        });
    }

    // Add Admin Dialog
    const addAdminButton = document.querySelector('.addAdminButton');
    const addAdminDialog = document.querySelector('.addAdminDialog');
    const closeAddAdminDialog = document.querySelector('.closeAddAdminDialog');

    if (addAdminButton && addAdminDialog) {
        addAdminButton.addEventListener('click', function () {
            addAdminDialog.showModal();
        });
    }

    if (closeAddAdminDialog && addAdminDialog) {
        closeAddAdminDialog.addEventListener('click', function () {
            addAdminDialog.close();
        });
    }

    // Edit Admin Dialog
    const editAdminButtons = document.querySelectorAll('.editAdminButton');
    const editAdminDialog = document.querySelector('.editAdminDialog');
    const closeEditAdminDialog = document.querySelector('.closeEditAdminDialog');
    const editAdminForm = document.querySelector('.editAdminForm');

    editAdminButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');

            if (editAdminForm) {
                editAdminForm.action = `/admin/users/${id}`;
            }

            const usernameInput = document.querySelector('#adminUsername');
            if (usernameInput) usernameInput.value = username;

            if (editAdminDialog) {
                editAdminDialog.showModal();
            }
        });
    });

    if (closeEditAdminDialog && editAdminDialog) {
        closeEditAdminDialog.addEventListener('click', function () {
            editAdminDialog.close();
        });
    }
});
