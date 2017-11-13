CREATE TABLE user (
 seq integer PRIMARY KEY,
 name text NOT NULL,
 password text NOT NULL,
 social_no text NOT NULL, /* this will be encoded fully */
 email text NOT NULL,
 created_at text NOT NULL,
 updated_at text NOT NULL
)