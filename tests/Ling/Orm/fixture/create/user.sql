CREATE TABLE user (
 seq integer PRIMARY KEY,
 name text NOT NULL,
 password text NOT NULL,
 email text NOT NULL,
 auth_key text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)