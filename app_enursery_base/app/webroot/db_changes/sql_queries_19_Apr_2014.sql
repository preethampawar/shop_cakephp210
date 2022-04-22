-- new columns in sites table
ALTER TABLE sites ADD COLUMN suspended TINYINT(1) DEFAULT 0; 
ALTER TABLE sites ADD COLUMN show_in_clients_list TINYINT(1) DEFAULT 0; 
ALTER TABLE sites ADD COLUMN under_maintenance TINYINT(1) DEFAULT 0; 

ALTER TABLE sites ADD COLUMN embed_map TEXT; 