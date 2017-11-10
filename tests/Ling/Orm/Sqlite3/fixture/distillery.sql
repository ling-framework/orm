CREATE TABLE distillery (
 seq integer PRIMARY KEY,
 name text NOT NULL,
 country text NOT NULL,
 region text,
 created_at text NOT NULL,
 updated_at text NOT NULL
);

insert into distillery values
(1, 'laphroaig', 'uk', 'islay', DateTime('now'), DateTime('now')),
(2, 'edradour', 'uk', 'highlands', DateTime('now'), DateTime('now')),
(3, 'yamazaki', 'jp', 'kyoto', DateTime('now'), DateTime('now')),
(4, 'kavalan', 'tw', NULL, DateTime('now'), DateTime('now')),
(5, 'ragnaud sabourin', 'fr', 'cognac', DateTime('now'), DateTime('now')),
(6, 'caroni', 'fr', NULL, DateTime('now'), DateTime('now')),
(7, 'bruichladdich', 'uk', 'islay', DateTime('now'), DateTime('now')),
(8, 'caol ila', 'uk', 'islay', DateTime('now'), DateTime('now'));


CREATE TABLE brand (
 seq integer PRIMARY KEY,
 distillery_seq integer NOT NULL,
 name text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)

insert into brand values
(1, 1, 'laphroaig', DateTime('now'), DateTime('now')),
(2, 2, 'edradour', DateTime('now'), DateTime('now')),
(3, 2, 'ballechin', DateTime('now'), DateTime('now')),
(4, 3, 'yamazaki', DateTime('now'), DateTime('now')),
(5, 3, 'hibiki', DateTime('now'), DateTime('now')),
(6, 3, 'hakusyu', DateTime('now'), DateTime('now')),
(7, 3, 'chita', DateTime('now'), DateTime('now')),
(8, 4, 'kavalan', DateTime('now'), DateTime('now')),
(9, 4, 'solist', DateTime('now'), DateTime('now')),
(10, 5, 'ragnaud sabourin', DateTime('now'), DateTime('now')),
(11, 6, 'caroni', DateTime('now'), DateTime('now')),
(12, 7, 'bruichladdich', DateTime('now'), DateTime('now')),
(13, 7, 'port charlotte', DateTime('now'), DateTime('now')),
(14, 7, 'octomore', DateTime('now'), DateTime('now')),
(15, 8, 'caol ila', DateTime('now'), DateTime('now'));



CREATE TABLE bottler (
 seq integer PRIMARY KEY,
 name text NOT NULL,
 country text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)

insert into bottler values
(1, 'adelphi', 'uk', DateTime('now'), DateTime('now')),
(2, 'cadenhead', 'uk', DateTime('now'), DateTime('now')),
(3, 'hunter laing', 'uk', DateTime('now'), DateTime('now')),
(4, 'g&m', 'uk', DateTime('now'), DateTime('now')),
(5, 'acorn', 'jp', DateTime('now'), DateTime('now')),
(6, 'samaroli', 'uk', DateTime('now'), DateTime('now')),
(7, 'chieftains', 'uk', DateTime('now'), DateTime('now')),
(8, 'the whisky agency', 'de', DateTime('now'), DateTime('now')),
(9, 'whiskyfind', 'tw', DateTime('now'), DateTime('now')),
(10, 'the maltman', 'uk', DateTime('now'), DateTime('now')),
(11, 'speciality drinks', 'uk', DateTime('now'), DateTime('now'));



CREATE TABLE bottler_brand (
 seq integer PRIMARY KEY,
 bottler_seq integer NOT NULL,
 name text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)

insert into bottler_brand values
(1, 1, 'adelphi', DateTime('now'), DateTime('now')),
(2, 2, 'cadenhead', DateTime('now'), DateTime('now')),
(3, 3, 'old malt cask', DateTime('now'), DateTime('now')),
(4, 3, 'old & rare', DateTime('now'), DateTime('now')),
(5, 4, 'cask strength', DateTime('now'), DateTime('now')),
(6, 4, 'exclusive', DateTime('now'), DateTime('now')),
(7, 4, 'from speiside', DateTime('now'), DateTime('now')),
(8, 5, 'friends of cask', DateTime('now'), DateTime('now')),
(9, 5, 'natural malt selection', DateTime('now'), DateTime('now')),
(10, 6, 'samaroli', DateTime('now'), DateTime('now')),
(11, 7, 'chieftains', DateTime('now'), DateTime('now')),
(12, 8, 'the whisky agency', DateTime('now'), DateTime('now')),
(13, 9, 'whiskyfind', DateTime('now'), DateTime('now'));
(14, 10, 'the maltman', DateTime('now'), DateTime('now')),
(15, 11, 'elements of islay', DateTime('now'), DateTime('now'));



CREATE TABLE importer (
 seq integer PRIMARY KEY,
 name text NOT NULL,
 country text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)

insert into importer values
(1, 'jis', 'jp', DateTime('now'), DateTime('now')),
(2, 'three rivers', 'jp', DateTime('now'), DateTime('now')),
(3, 'santory', 'jp', DateTime('now'), DateTime('now')),
(4, 'whisky hoop', 'jp', DateTime('now'), DateTime('now')),
(5, 'pernod recard', 'us', DateTime('now'), DateTime('now')),
(6, 'mhd', 'fr', DateTime('now'), DateTime('now')),
(7, 'the bow bar', 'jp', DateTime('now'), DateTime('now')),
(8, 'bar lamp', 'jp', DateTime('now'), DateTime('now'));



CREATE TABLE bottle (
 seq integer PRIMARY KEY,
 name text NOT NULL,
 liquor_type text NOT NULL,
 cask_type text,
 year integer,
 age integer,
 strength real NOT NULL,
 volume int NOT NULL,
 brand_seq integer,
 bottler_brand_seq integer,
 importer_seq integer,
 created_at text NOT NULL,
 updated_at text NOT NULL
)

insert into bottle values
(1, 'Hunter Laing Laphroaig 2001', 'whisky', NULL, 2001, 15, 50.0, 700, 1, 3, NULL, DateTime('now'), DateTime('now')),
(2, 'GM Caol Ila 2007', 'whisky', 'refill sherry h/h', 2007, 7, 57.1, 700, 15, 4, 1, DateTime('now'), DateTime('now')),
(3, 'GM Caol Ila Cask Strength 1981', 'whisky', NULL, 1981, 34, 59.0, 700, 15, 4, NULL, DateTime('now'), DateTime('now')),
(4, 'Ragnaud Sabourin Florilege', 'cognac', NULL, NULL, 45, 46.0, 700, 10, NULL, NULL, DateTime('now'), DateTime('now')),
(5, 'EDRADOUR 16yo BAROLO CASK FINISH', 'whisky', 'barolo cask finish', 2000, 16, 56.7, 700, 2, NULL, NULL, DateTime('now'), DateTime('now')),
(6, 'EDRADOUR Ballechin Double Malt', 'whisky', NULL, NULL, NULL, 46.0, 700, 3, NULL, NULL, DateTime('now'), DateTime('now')),
(7, 'yamazaki 12yo', 'whisky', NULL, NULL, 12, 43.0, 700, 4, NULL, NULL, DateTime('now'), DateTime('now')),
(8, 'yamazaki 18yo', 'whisky', NULL, NULL, 18, 43.0, 700, 4, NULL, NULL, DateTime('now'), DateTime('now')),
(9, 'yamazaki 25yo', 'whisky', NULL, NULL, 25, 43.0, 700, 4, NULL, NULL, DateTime('now'), DateTime('now')),
(10, 'hibiki japanese harmony', 'whisky', NULL, NULL, NULL, 43.0, 700, 5, NULL, NULL, DateTime('now'), DateTime('now')),
(11, 'hibiki 17yo', 'whisky', NULL, NULL, 17, 43.0, 700, 5, NULL, NULL, DateTime('now'), DateTime('now')),
(12, 'hibiki 21yo', 'whisky', NULL, NULL, 21, 43.0, 700, 5, NULL, NULL, DateTime('now'), DateTime('now')),
(13, 'hibiki 30yo', 'whisky', NULL, NULL, 30, 43.0, 700, 5, NULL, NULL, DateTime('now'), DateTime('now')),
(14, 'hakusyu 12yo', 'whisky', NULL, NULL, 12, 43.0, 700, 6, NULL, NULL, DateTime('now'), DateTime('now')),
(15, 'hakusyu 18yo', 'whisky', NULL, NULL, 18, 43.0, 700, 6, NULL, NULL, DateTime('now'), DateTime('now')),
(16, 'hakusyu 25yo', 'whisky', NULL, NULL, 25, 43.0, 700, 6, NULL, NULL, DateTime('now'), DateTime('now')),
(17, 'chita', 'whisky', NULL, NULL, NULL, 43.0, 700, 7, NULL, NULL, DateTime('now'), DateTime('now')),
(18, 'kavalan classic', 'whisky', NULL, NULL, 40.0, 700, 8, NULL, NULL, DateTime('now'), DateTime('now')),
(19, 'kavalan solist ex-bourbon single cask strength', 'whisky', 'burbon', NULL, 58.6, 700, 9, NULL, NULL, DateTime('now'), DateTime('now')),
(20, 'kavalan solist PORT single cask strength', 'whisky', 'port', NULL, 57.3, 700, 9, NULL, NULL, DateTime('now'), DateTime('now')),
(21, 'bruichladdich the laddie eight', 'whisky', NULL, 8, 50.0, 700, 12, NULL, NULL, DateTime('now'), DateTime('now')),
(22, 'bruichladdich lslay barley', 'whisky', NULL, 2010, 8, 50.0, 700, 12, NULL, NULL, DateTime('now'), DateTime('now')),
(23, 'port charlotte pc12 oileanach furachail', 'whisky', NULL, NULL, 12, 50.0, 700, 13, NULL, NULL, DateTime('now'), DateTime('now')),
(24, 'the maltman port charlotte 2002 13yo bourbon cask #238', 'whisky', 'bourbon', 2002, 13, 53.2, 700, 13, 14, NULL, DateTime('now'), DateTime('now')),
(25, 'octomore 08.2', 'whisky', 'sauternes', 2008, 8, 58.4, 700, 14, NULL, NULL, DateTime('now'), DateTime('now')),
(26, 'octomore 08.3', 'whisky', 'bourbon', 2011, 5, 61.2, 700, 14, NULL, NULL, DateTime('now'), DateTime('now')),
(27, 'speciality drinks elements of islay oc4', 'whisky', '', NULL, NULL, 63.5, 700, 14, 15, NULL, DateTime('now'), DateTime('now')),
(28, 'hunter laing omc caol ila', 'whisky', '', NULL, 8, 50.0, 700, 15, 3, NULL, DateTime('now'), DateTime('now')),
(29, 'caol ila 12yo', 'whisky', '', NULL, 12, 43.0, 700, 15, NULL, NULL, DateTime('now'), DateTime('now')),
(30, 'caol ila 18yo', 'whisky', '', NULL, 18, 43.0, 700, 15, NULL, NULL, DateTime('now'), DateTime('now')),
(31, 'caol ila 25yo', 'whisky', '', NULL, 25, 43.0, 700, 15, NULL, NULL, DateTime('now'), DateTime('now')),
(32, 'caroni 1997', 'rum', '', 1997, 18, 57.9, 700, 11, NULL, NULL, DateTime('now'), DateTime('now')),
(33, 'lmdw & velier caroni 1996', 'rum', '', 1996, 20, 70.1, 700, 11, NULL, NULL, DateTime('now'), DateTime('now'));



CREATE TABLE shop (
 seq integer PRIMARY KEY,
 name text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)

insert into shop values
(1, 'shinanoya', DateTime('now'), DateTime('now')),
(2, 'big ginza', DateTime('now'), DateTime('now')),
(3, 'whisky plus', DateTime('now'), DateTime('now')),
(4, 'tanakaya', DateTime('now'), DateTime('now')),
(5, 'liquor hasegawa', DateTime('now'), DateTime('now'));

CREATE TABLE stock (
 seq integer PRIMARY KEY,
 bottle_seq integer NOT NULL,
 shop_seq integer NOT NULL,
 stock integer default 0,
 price integer default 0,
 created_at text NOT NULL,
 updated_at text NOT NULL,
 constraint bottle_shop UNIQUE (bottle_seq, shop_seq)
)

insert into stock values
(1, 1, 1, 10, 10000, DateTime('now'), DateTime('now')),
(2, 1, 2, 30, 10000, DateTime('now'), DateTime('now')),
(3, 1, 3, 10, 10000, DateTime('now'), DateTime('now')),
(4, 1, 4, 20, 10000, DateTime('now'), DateTime('now')),
(5, 1, 5, 0, 10000, DateTime('now'), DateTime('now')),
(6, 2, 1, 10, 10000, DateTime('now'), DateTime('now')),
(7, 2, 3, 5, 20000, DateTime('now'), DateTime('now')),
(8, 2, 5, 0, 30000, DateTime('now'), DateTime('now')),
(9, 3, 4, 3, 40000, DateTime('now'), DateTime('now')),
(10, 3, 5, 1, 10000, DateTime('now'), DateTime('now')),
(11, 4, 1, 10, 20000, DateTime('now'), DateTime('now')),
(12, 4, 3, 11, 30000, DateTime('now'), DateTime('now')),
(13, 4, 4, 12, 40000, DateTime('now'), DateTime('now')),
(14, 5, 1, 13, 10000, DateTime('now'), DateTime('now')),
(15, 5, 2, 14, 30000, DateTime('now'), DateTime('now')),
(16, 5, 3, 15, 20000, DateTime('now'), DateTime('now')),
(17, 5, 4, 16, 10000, DateTime('now'), DateTime('now')),
(18, 6, 2, 1, 10000, DateTime('now'), DateTime('now')),
(19, 6, 3, 2, 20000, DateTime('now'), DateTime('now')),
(20, 6, 4, 3, 10000, DateTime('now'), DateTime('now')),
(21, 8, 1, 3, 15000, DateTime('now'), DateTime('now')),
(22, 9, 1, 3, 17000, DateTime('now'), DateTime('now')),
(23, 10, 3, 50, 17000, DateTime('now'), DateTime('now')),
(24, 11, 2, 10, 17000, DateTime('now'), DateTime('now')),
(25, 13, 1, 3, 17000, DateTime('now'), DateTime('now')),
(26, 14, 3, 10, 16000, DateTime('now'), DateTime('now')),
(27, 14, 5, 15, 15000, DateTime('now'), DateTime('now')),
(28, 15, 1, 20, 14000, DateTime('now'), DateTime('now')),
(29, 16, 1, 10, 13000, DateTime('now'), DateTime('now')),
(30, 16, 2, 15, 12000, DateTime('now'), DateTime('now')),
(31, 17, 4, 7, 11000, DateTime('now'), DateTime('now')),
(32, 19, 1, 3, 7000, DateTime('now'), DateTime('now')),
(33, 20, 1, 5, 17000, DateTime('now'), DateTime('now')),
(34, 20, 2, 1, 18000, DateTime('now'), DateTime('now')),
(35, 21, 4, 0, 19000, DateTime('now'), DateTime('now')),
(36, 22, 5, 6, 20000, DateTime('now'), DateTime('now')),
(37, 24, 2, 10, 17000, DateTime('now'), DateTime('now')),
(38, 24, 3, 3, 18000, DateTime('now'), DateTime('now')),
(39, 25, 1, 30, 19000, DateTime('now'), DateTime('now')),
(40, 25, 2, 20, 30000, DateTime('now'), DateTime('now')),
(41, 26, 4, 1, 12000, DateTime('now'), DateTime('now')),
(42, 27, 2, 5, 11000, DateTime('now'), DateTime('now')),
(43, 28, 2, 7, 10000, DateTime('now'), DateTime('now')),
(44, 28, 2, 11, 9000, DateTime('now'), DateTime('now')),
(45, 29, 2, 8, 20000, DateTime('now'), DateTime('now')),
(45, 31, 2, 7, 22000, DateTime('now'), DateTime('now')),
(45, 31, 3, 10, 13000, DateTime('now'), DateTime('now')),
(45, 32, 1, 11, 10000, DateTime('now'), DateTime('now')),
(45, 33, 4, 12, 11000, DateTime('now'), DateTime('now')),
(45, 33, 5, 13, 12000, DateTime('now'), DateTime('now'));



