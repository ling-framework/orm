CREATE TABLE stock (
 id integer PRIMARY KEY,
 bottle_id integer NOT NULL,
 shop_id integer NOT NULL,
 stock integer default 0,
 price integer default 0,
 created_at text NOT NULL,
 updated_at text NOT NULL,
 constraint bottle_shop UNIQUE (bottle_id, shop_id)
)