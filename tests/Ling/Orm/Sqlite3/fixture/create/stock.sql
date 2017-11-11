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