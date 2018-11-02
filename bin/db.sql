-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `atf_lesson`;
CREATE TABLE `atf_lesson` (
  `lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `atf_lesson` (`lesson_id`, `name`) VALUES
(1,	'písmena j, f, d, k'),
(2,	'písmena s, l'),
(3,	'cvičné texty'),
(4,	'složitější texty');

DROP TABLE IF EXISTS `atf_task`;
CREATE TABLE `atf_task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `content` text COLLATE utf8_czech_ci NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`task_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `atf_task_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `atf_lesson` (`lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `atf_task` (`task_id`, `lesson_id`, `name`, `content`, `order`) VALUES
(1,	1,	'f, j',	'fff jjj fff jjj fff jjj fff jjj fff jjj fff jjj fff jjj',	1),
(2,	1,	'',	'f f f j j j f f f j j j f f f j j j f f f j j j f f f j',	2),
(3,	2,	's, l',	'sss lll sss lll sss lll sss lll sss lll sss lll sss lll',	1),
(4,	2,	'',	's s s l l l s s s l l l s s s l l l s s s l l l s s s l',	2),
(5,	3,	'',	'Situace ve složení výdajových položek byla poněkud složitější,\nprotože se během roku několikrát měnily plány výroby i složení\npracovních sil. Doprostřed sálu přitáhli žáci školy rovněž tu\nvelkou žíněnku. Michal měl už od mládí velice rád žitný chléb. ',	1),
(6,	1,	'd, k',	'ddd kkk ddd kkk ddd kkk ddd kkk ddd kkk ddd kkk ddd kkk',	3),
(7,	4,	'',	'Podstata knihtisku (tj. mechanické rozmnožování psaného textu za pomoci\npohyblivých liter, tiskařského lisu a černě) byla známa nejdříve Číňanům.\nJiž od 8. stol. tiskli z desek a od 11. stol. pohyblivými typy. V letech\n1403 - 1405 byl knihtisk objeven v Koreji. Evropa považuje za vynálezce\nknihtisku (asi r. 1444) mohučského zlatníka Gensfleische, zv. Gutenberga. ',	1),
(9,	1,	'',	'd d d k k k d d d k k k d d d k k k d d d k k k d d d k',	4),
(10,	1,	'f, k',	'fk kf fk kf fk kf fk kf fk kf fk kf fk kf fk kf fk kf',	5),
(11,	1,	'j, k',	'jk kj jk kj jk kj jk kj jk kj jk kj jk kj jk kj jk kj',	6),
(12,	1,	'j, f, d, k',	'kfdj jfkd kdfj djfk kfdj djfk kdfj jfdk kfdj jfdk kdfj',	7),
(13,	2,	's, d',	'sd ds sd ds sd ds sd ds sd ds sd ds sd ds sd ds sd ds sd',	3),
(14,	2,	's, l',	'sl ls sl ls sl ls sl ls sl ls sl ls sl ls sl ls sl ls sl',	4),
(15,	2,	'l, j',	'lj jl lj jl lj jl lj jl lj jl lj jl lj jl lj jl lj jl lj',	5),
(16,	2,	's, j',	'sj js sj js sj js sj js sj js sj js sj js sj js sj js sj',	6),
(17,	2,	'l, k',	'lk kl lk kl lk kl lk kl lk kl lk kl lk kl lk kl lk kl lk',	7),
(18,	3,	'',	'Své pražské jednání právě zahájili odborníci v otázkách životního\nprostředí. Při stavbě nové sportovní haly použil též alternativní\nřešení s železobetonovou konstrukcí. Také již školili naše žáky\na žákyně pro požární hlídku, která - bude-li nutné - zasáhne. ',	2),
(19,	3,	'',	'Náš vyšetřovatel Božetěch Kožešník už podruhé v životě měl krajně\nnepříjemnou práci - musel vyšetřovat vraždu. K našemu pobřeží se\npak už žádný žralok nepřiblížil. Manželé Zdražilovi si stěžují na\nsousedky. Splnit váš požadavek považujeme prostě za nemožné. ',	3),
(20,	3,	'',	'Naše Blažena nyní pracuje v kožešnictví. Do žní má naše traktorová\nstanice opravit už jenom tři žací stroje. Není-li žalobce, není ani\nsoudce. Pacienti přísně dodržovali životosprávu. Ten váš požadavek\npřesahuje kapacitní možnosti rozmnožovny. Rozžhavil se vzteky doběla. ',	4),
(21,	3,	'',	'Na Vánoce dostal Jan pěkné béžové pyžamo. Někam založil podkladové\nmateriály. Přes cestu běžel ježek. Ten váš žurnalista se mi tedy\npříliš nezamlouvá. Dřív se na vojně sloužívalo mnohem déle než dnes.\nVyvíjeli nový druh výztuže. Celý život s ním prý měla velké soužení. ',	5),
(22,	3,	'',	'Ty lakýrky s koženou podrážkou krásně kloužou po parketu. Zabývali\nse možnostmi využití zbývajících rezerv. Požádáte-li správce, jistě\nvám vybere i správné lyže. Zítra odjíždím do Rožnova pod Radhoštěm.\nManžel má chřipku, leží už druhý týden. Požár se rychle rozšiřoval. ',	6),
(23,	3,	'',	'Snažíme se využívat i moderní techniky. Kos požíral žížalu. Vysokého\nvěku se dožívají například želvy. Božena Brožová požádala o odložení\npřijímací zkoušky. V požadovaném termínu a též v předepsaném množství\nvyhotovení dodal projektant veškerou dokumentaci k výstavbě železáren. ',	7),
(24,	3,	'',	'Ty staré železárny už dávno dosloužily a kvalitní železo je pro naše\nhospodářství životně závažnou nutností. Nová železárna předpokládá\ntéž lepší využití železného šrotu. Na jeho sběru mají zásluhy i žáci\nnaší školy. Tím přispívají ke zlepšení životního prostředí naší země. ',	8),
(25,	3,	'',	'Vaše ceny jsou přemrštěné. Vlak do Košic odjel před šesti minutami.\nNeporuší-li některá ze stran dohodu o příměří, zvyšuje se naděje na\nmírové řešení sporu. Příliš šetrně se ovšem nechová. Naše Maruška se\nvelice bojí myší. Jen klidně všichni vstupte dál, vůbec nás nerušíte. ',	9),
(26,	4,	'',	'V Rusku byl knihtisk zaveden asi roku 1558. Zpočátku byl knihtisk pokládán\njen za svobodné umění a teprve v 19. století se stal průmyslem. Přispěly\nk tomu technické vynálezy jako rychlolis, odlévací stroj, sázecí stroje,\nofset, rotačka a chemigrafická reprodukce. První české knihy byly tištěny\nzřejmě v letech 1459 - 1480. Prvním českým tiskem je Kronika trojánská. ',	2),
(27,	4,	'',	'Se svou nepatrnou flotilou vyplul dne 3. srpna 1492 z přístavu Palos,\ndostihl Kanárských ostrovů a odtud plul dále na západ. Po desetitýdenní\nplavbě shlédl poprvé zemi dne 12. října 1492, vystoupil na ni a zabral\nji jménem Španělska. Byl to ostrov Guanahani v Bahamském souostroví, dnes ',	3),
(28,	4,	'',	'Je zajímavé, co všechno ukládá zákon č. 59/1965 Sb., o výkonu trestu\nodnětí svobody. V § 2 je uvedeno, že základním organizačním článkem,\nv němž se provádí nápravně výchovná činnost, je kolektiv odsouzených.\nTi jsou do kolektivů zařazováni s přihlédnutím k jejich osobnosti,\nv počtu 20 až 30 odsouzených na jednoho samostatného vychovatele. ',	4),
(29,	4,	'',	'Jak může ocelářská firma reagovat na změnu poptávky v čase? To si\nmůžeme ukázat na příkladu Poldi Kladno, která např. provozuje své\nhutnické pece na 70 % kapacity, když náhle a neočekávaně dojde ke\nzvýšení poptávky po oceli v důsledku havárie v závodě konkurenta.\nV období několika dnů nemůže ocelářská firma svou výrobu přizpůsobit. ',	5),
(30,	4,	'',	'Účelem zákona České národní rady č. 246/1992 Sb., na ochranu zvířat\nproti týrání, je chránit zvířata, jež jsou živými tvory schopnými\npociťovat bolest a utrpení, před týráním, poškozováním jejich zdraví\na jejich usmrcením bez důvodu, pokud byly způsobeny, byť z nedbalosti,\nčlověkem. V § 4 tohoto zákona je uvedeno, co se považuje za týrání. ',	6);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `role` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`) VALUES
(1,	'wujido',	'$2y$10$VpHMPRHlPscdAFF6yBCZi.13VsNrsRL6g8CvMM/m/Mr/Yjub42wwO',	'wujido20@gmail.com',	0);

-- 2018-11-02 22:33:36
