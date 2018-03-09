CREATE TABLE brand (
 id integer PRIMARY KEY,
 distillery_id integer NOT NULL,
 name text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)