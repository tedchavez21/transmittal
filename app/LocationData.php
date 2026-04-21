<?php

namespace App;

class LocationData
{
    public static function getAll()
    {
        return [
            'Nueva Ecija' => [
                'Aliaga' => [
                    'Betes', 'Bibiclat', 'Bucot', 'La Purisima', 'Magsaysay', 'Macabucod', 'Pantoc',
                    'Poblacion Centro', 'Poblacion East I', 'Poblacion East II', 'Poblacion West III',
                    'Poblacion West IV', 'San Carlos', 'San Emiliano', 'San Eustacio', 'San Felipe Bata',
                    'San Felipe Matanda', 'San Juan', 'San Pablo Bata', 'San Pablo Matanda', 'Santa Monica',
                    'Santiago', 'Santo Rosario', 'Santo Tomas', 'Sunson', 'Umangan'
                ],
                'Bongabon' => [
                    'Antipolo', 'Ariendo', 'Bantug', 'Calaanan', 'Commercial', 'Cruz', 'Digmala', 'Curva',
                    'Kaingin', 'Labi', 'Larcon', 'Lusok', 'Macabaclay', 'Magtanggol', 'Mantile', 'Olivete',
                    'Palo Maria', 'Pesa', 'Rizal', 'Sampalucan', 'San Roque', 'Santor', 'Sinipit',
                    'Sisilang na Ligaya', 'Social', 'Tugatug', 'Tulay na Bato', 'Vega'
                ],
                'City of Cabanatuan' => [
                    'Aduas Centro', 'Bagong Sikat', 'Bagong Buhay', 'Bakero', 'Bakod Bayan', 'Balite', 'Bangad',
                    'Bantug Bulalo', 'Bantug Norte', 'Barlis', 'Barrera District', 'Bernardo District', 'Bitas',
                    'Bonifacio District', 'Buliran', 'Caalibangbangan', 'Cabu', 'Campo Tinio', 'Kapitan Pepe',
                    'Cinco-Cinco', 'City Supermarket', 'Caudillo', 'Communal', 'Cruz Roja', 'Daang Sarile',
                    'Dalampang', 'Dicarma', 'Dimasalang', 'Dionisio S. Garcia', 'Fatima', 'General Luna',
                    'Ibabao Bana', 'Imelda District', 'Isla', 'Calawagan', 'Kalikid Norte', 'Kalikid Sur',
                    'Lagare', 'M. S. Garcia', 'Mabini Extension', 'Mabini Homesite', 'Macatbong',
                    'Magsaysay District', 'Matadero', 'Lourdes', 'Mayapyap Norte', 'Mayapyap Sur',
                    'Melojavilla', 'Obrero', 'Padre Crisostomo', 'Pagas', 'Palagay', 'Pamaldan', 'Pangatian',
                    'Patalac', 'Polilio', 'Pula', 'Quezon District', 'Rizdelis', 'Samon', 'San Isidro',
                    'San Josef Norte', 'San Josef Sur', 'San Juan Pob.', 'San Roque Norte', 'San Roque Sur',
                    'Sanbermicristi', 'Sangitan', 'Santa Arcadia', 'Sumacab Norte', 'Valdefuente', 'Valle Cruz',
                    'Vijandre District', 'Villa Ofelia-Caridad', 'Zulueta District', 'Nabao', 'Padre Burgos',
                    'Talipapa', 'Aduas Norte', 'Aduas Sur', 'Hermogenes C. Concepcion, Sr.', 'Sapang',
                    'Sumacab Este', 'Sumacab South', 'Caridad', 'Magsaysay South', 'Maria Theresa',
                    'Sangitan East', 'Santo Niño'
                ],
                'Cabiao' => [
                    'Bagong Buhay', 'Bagong Sikat', 'Bagong Silang', 'Concepcion', 'Entablado', 'Maligaya',
                    'Natividad North', 'Natividad South', 'Palasinan', 'San Antonio', 'San Fernando Norte',
                    'San Fernando Sur', 'San Gregorio', 'San Juan North', 'San Juan South', 'San Roque',
                    'San Vicente', 'Santa Rita', 'Sinipit', 'Polilio', 'San Carlos', 'Santa Isabel', 'Santa Ines'
                ],
                'Carranglan' => [
                    'R.A.Padilla', 'Bantug', 'Bunga', 'Burgos', 'Capintalan', 'Joson', 'General Luna', 'Minuli',
                    'Piut', 'Puncan', 'Putlan', 'Salazar', 'San Agustin', 'T. L. Padilla Pob.',
                    'F. C. Otic Pob.', 'D. L. Maglanoc Pob.', 'G. S. Rosario Pob.'
                ],
                'Cuyapo' => [
                    'Baloy', 'Bambanaba', 'Bantug', 'Bentigan', 'Bibiclat', 'Bonifacio', 'Bued', 'Bulala',
                    'Burgos', 'Cabileo', 'Cabatuan', 'Cacapasan', 'Calancuasan Norte', 'Calancuasan Sur',
                    'Colosboa', 'Columbitin', 'Curva', 'District I', 'District II', 'District IV', 'District V',
                    'District VI', 'District VII', 'District VIII', 'Landig', 'Latap', 'Loob', 'Luna',
                    'Malbeg-Patalan', 'Malineng', 'Matindeg', 'Maycaban', 'Nagcuralan', 'Nagmisahan',
                    'Paitan Norte', 'Paitan Sur', 'Piglisan', 'Pugo', 'Rizal', 'Sabit', 'Salagusog',
                    'San Antonio', 'San Jose', 'San Juan', 'Santa Clara', 'Santa Cruz', 'Simimbaan',
                    'Tagtagumbao', 'Tutuloy', 'Ungab', 'Villaflores'
                ],
                'Gabaldon' => [
                    'Bagong Sikat', 'Bagting', 'Bantug', 'Bitulok', 'Bugnan', 'Calabasa', 'Camachile', 'Cuyapa',
                    'Ligaya', 'Macasandal', 'Malinao', 'Pantoc', 'Pinamalisan', 'South Poblacion', 'Sawmill',
                    'Tagumpay'
                ],
                'City of Gapan' => [
                    'Bayanihan', 'Bulak', 'Kapalangan', 'Mahipon', 'Malimba', 'Mangino', 'Marelo', 'Pambuan',
                    'Parcutela', 'San Lorenzo', 'San Nicolas', 'San Roque', 'San Vicente', 'Santa Cruz',
                    'Santo Cristo Norte', 'Santo Cristo Sur', 'Santo Niño', 'Makabaclay', 'Balante', 'Bungo',
                    'Mabunga', 'Maburak', 'Puting Tubig'
                ],
                'General Mamerto Natividad' => [
                    'Balangkare Norte', 'Balangkare Sur', 'Balaring', 'Belen', 'Bravo', 'Burol', 'Kabulihan',
                    'Mag-asawang Sampaloc', 'Manarog', 'Mataas na Kahoy', 'Panacsac', 'Picaleon', 'Pinahan',
                    'Platero', 'Poblacion', 'Pula', 'Pulong Singkamas', 'Sapang Bato', 'Talabutab Norte',
                    'Talabutab Sur'
                ],
                'General Tinio' => [
                    'Bago', 'Concepcion', 'Nazareth', 'Padolina', 'Pias', 'San Pedro', 'Poblacion East',
                    'Poblacion West', 'Rio Chico', 'Poblacion Central', 'Pulong Matong', 'Sampaguita', 'Palale'
                ],
                'Guimba' => [
                    'Agcano', 'Ayos Lomboy', 'Bacayao', 'Bagong Barrio', 'Balbalino', 'Balingog East',
                    'Balingog West', 'Banitan', 'Bantug', 'Bulakid', 'Caballero', 'Cabaruan', 'Caingin Tabing Ilog',
                    'Calem', 'Camiing', 'Cardinal', 'Casongsong', 'Catimon', 'Cavite', 'Cawayan Bugtong',
                    'Consuelo', 'Culong', 'Escano', 'Faigal', 'Galvan', 'Guiset', 'Lamorito', 'Lennec',
                    'Macamias', 'Macapabellag', 'Macatcatuit', 'Manacsac', 'Manggang Marikit', 'Maturanoc',
                    'Maybubon', 'Naglabrahan', 'Nagpandayan', 'Narvacan I', 'Narvacan II', 'Pacac', 'Partida I',
                    'Partida II', 'Pasong Inchic', 'Saint John District', 'San Agustin', 'San Andres',
                    'San Bernardino', 'San Marcelino', 'San Miguel', 'San Rafael', 'San Roque', 'Santa Ana',
                    'Santa Cruz', 'Santa Lucia', 'Santa Veronica District', 'Santo Cristo District',
                    'Saranay District', 'Sinulatan', 'Subol', 'Tampac I', 'Tampac II & III', 'Triala', 'Yuson',
                    'Bunol'
                ],
                'Jaen' => [
                    'Calabasa', 'Dampulan', 'Hilera', 'Imbunia', 'Imelda Pob.', 'Lambakin', 'Langla', 'Magsalisi',
                    'Malabon-Kaingin', 'Marawa', 'Don Mariano Marcos', 'San Josef', 'Niyugan', 'Pamacpacan',
                    'Pakol', 'Pinanggaan', 'Ulanin-Pitak', 'Putlod', 'Ocampo-Rivera District', 'San Jose',
                    'San Pablo', 'San Roque', 'San Vicente', 'Santa Rita', 'Santo Tomas North', 'Santo Tomas South',
                    'Sapang'
                ],
                'Laur' => [
                    'Barangay I', 'Barangay II', 'Barangay III', 'Barangay IV', 'Betania', 'Canantong', 'Nauzon',
                    'Pangarulong', 'Pinagbayanan', 'Sagana', 'San Fernando', 'San Isidro', 'San Josef', 'San Juan',
                    'San Vicente', 'Siclong', 'San Felipe'
                ],
                'Licab' => [
                    'Linao', 'Poblacion Norte', 'Poblacion Sur', 'San Casimiro', 'San Cristobal', 'San Jose',
                    'San Juan', 'Santa Maria', 'Tabing Ilog', 'Villarosa', 'Aquino'
                ],
                'Llanera' => [
                    'A. Bonifacio', 'Caridad Norte', 'Caridad Sur', 'Casile', 'Florida Blanca', 'General Luna',
                    'General Ricarte', 'Gomez', 'Inanama', 'Ligaya', 'Mabini', 'Murcon', 'Plaridel', 'Bagumbayan',
                    'San Felipe', 'San Francisco', 'San Nicolas', 'San Vicente', 'Santa Barbara', 'Victoria',
                    'Villa Viniegas', 'Bosque'
                ],
                'Lupao' => [
                    'Agupalo Este', 'Agupalo Weste', 'Alalay Chica', 'Alalay Grande', 'J. U. Tienzo', 'Bagong Flores',
                    'Balbalungao', 'Burgos', 'Cordero', 'Mapangpang', 'Namulandayan', 'Parista', 'Poblacion East',
                    'Poblacion North', 'Poblacion South', 'Poblacion West', 'Salvacion I', 'Salvacion II',
                    'San Antonio Este', 'San Antonio Weste', 'San Isidro', 'San Pedro', 'San Roque', 'Santo Domingo'
                ],
                'Science City of Muñoz' => [
                    'Bagong Sikat', 'Balante', 'Bantug', 'Bical', 'Cabisuculan', 'Calabalabaan', 'Calisitan',
                    'Catalanacan', 'Curva', 'Franza', 'Gabaldon', 'Labney', 'Licaong', 'Linglingay',
                    'Mangandingay', 'Magtanggol', 'Maligaya', 'Mapangpang', 'Maragol', 'Matingkis', 'Naglabrahan',
                    'Palusapis', 'Pandalla', 'Poblacion East', 'Poblacion North', 'Poblacion South', 'Poblacion West',
                    'Rang-ayan', 'Rizal', 'San Andres', 'San Antonio', 'San Felipe', 'Sapang Cawayan', 'Villa Isla',
                    'Villa Nati', 'Villa Santos', 'Villa Cuizon'
                ],
                'Nampicuan' => [
                    'Alemania', 'Ambasador Alzate Village', 'Cabaducan East', 'Cabaducan West', 'Cabawangan',
                    'East Central Poblacion', 'Edy', 'Maeling', 'Mayantoc', 'Medico', 'Monic', 'North Poblacion',
                    'Northwest Poblacion', 'Estacion', 'West Poblacion', 'Recuerdo', 'South Central Poblacion',
                    'Southeast Poblacion', 'Southwest Poblacion', 'Tony', 'West Central Poblacion'
                ],
                'City of Palayan' => [
                    'Aulo', 'Bo. Militar', 'Ganaderia', 'Maligaya', 'Manacnac', 'Mapait', 'Marcos Village', 'Malate',
                    'Sapang Buho', 'Singalat', 'Atate', 'Caballero', 'Caimito', 'Doña Josefa', 'Imelda Valley',
                    'Langka', 'Santolan', 'Popolon Pagas', 'Bagong Buhay'
                ],
                'Pantabangan' => [
                    'Cadaclan', 'Cambitala', 'Conversion', 'Ganduz', 'Liberty', 'Malbang', 'Marikit', 'Napon-Napon',
                    'Poblacion East', 'Poblacion West', 'Sampaloc', 'San Juan', 'Villarica', 'Fatima'
                ],
                'Peñaranda' => [
                    'Callos', 'Las Piñas', 'Poblacion I', 'Poblacion II', 'Poblacion III', 'Poblacion IV', 'Santo Tomas',
                    'Sinasajan', 'San Josef', 'San Mariano'
                ],
                'Quezon' => [
                    'Bertese', 'Doña Lucia', 'Dulong Bayan', 'Ilog Baliwag', 'Barangay I', 'Barangay II', 'Pulong Bahay',
                    'San Alejandro', 'San Andres I', 'San Andres II', 'San Manuel', 'Santa Clara', 'Santa Rita',
                    'Santo Cristo', 'Santo Tomas Feria', 'San Miguel'
                ],
                'Rizal' => [
                    'Agbannawag', 'Bicos', 'Cabucbucan', 'Calaocan District', 'Canaan East', 'Canaan West',
                    'Casilagan', 'Aglipay', 'Del Pilar', 'Estrella', 'General Luna', 'Macapsing', 'Maligaya',
                    'Paco Roman', 'Pag-asa', 'Poblacion Central', 'Poblacion East', 'Poblacion Norte', 'Poblacion Sur',
                    'Poblacion West', 'Portal', 'San Esteban', 'Santa Monica', 'Villa Labrador', 'Villa Paraiso',
                    'San Gregorio'
                ],
                'San Antonio' => [
                    'Buliran', 'Cama Juan', 'Julo', 'Lawang Kupang', 'Luyos', 'Maugat', 'Panabingan', 'Papaya',
                    'Poblacion', 'San Francisco', 'San Jose', 'San Mariano', 'Santa Cruz', 'Santo Cristo',
                    'Santa Barbara', 'Tikiw'
                ],
                'San Isidro' => [
                    'Alua', 'Calaba', 'Malapit', 'Mangga', 'Poblacion', 'Pulo', 'San Roque', 'Sto. Cristo', 'Tabon'
                ],
                'San Jose City' => [
                    'A. Pascual', 'Abar Ist', 'Abar 2nd', 'Bagong Sikat', 'Caanawan', 'Calaocan', 'Camanacsacan',
                    'Culaylay', 'Dizol', 'Kaliwanagan', 'Kita-Kita', 'Malasin', 'Manicla', 'Palestina',
                    'Parang Mangga', 'Villa Joson', 'Pinili', 'Rafael Rueda, Sr. Pob.',
                    'Ferdinand E. Marcos Pob.', 'Canuto Ramos Pob.', 'Raymundo Eugenio Pob.',
                    'Crisanto Sanchez Pob.', 'Porais', 'San Agustin', 'San Juan', 'San Mauricio', 'Santo Niño 1st'
                ]
            ],
            'Aurora' => [
            ]
        ];
    }
}
