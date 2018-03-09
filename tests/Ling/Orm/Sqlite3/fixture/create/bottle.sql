CREATE TABLE bottle (
 id integer PRIMARY KEY,
 name text NOT NULL,
 liquor_type text NOT NULL,
 cask_type text,
 year integer,
 age integer,
 strength real NOT NULL,
 volume int NOT NULL,
 brand_id integer,
 bottler_brand_id integer,
 importer_id integer,
 created_at text NOT NULL,
 updated_at text NOT NULL
)