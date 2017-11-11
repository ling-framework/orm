CREATE TABLE bottler_brand (
 seq integer PRIMARY KEY,
 bottler_seq integer NOT NULL,
 name text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)