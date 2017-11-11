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