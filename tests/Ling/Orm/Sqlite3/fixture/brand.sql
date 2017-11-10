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
