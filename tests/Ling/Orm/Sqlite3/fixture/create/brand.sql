CREATE TABLE brand (
 seq integer PRIMARY KEY,
 distillery_seq integer NOT NULL,
 name text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)