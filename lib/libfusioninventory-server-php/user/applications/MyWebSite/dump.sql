BEGIN TRANSACTION;
CREATE TABLE machine(
idmachine INTEGER PRIMARY KEY AUTOINCREMENT, time);
CREATE TABLE section(
idsection INTEGER PRIMARY KEY AUTOINCREMENT,
sectionName NOT NULL,
sectionData,
idmachine INTEGER NOT NULL CONSTRAINT fk_idmachine REFERENCES machine(idmachine));
CREATE TABLE changeslog(
idchange INTEGER PRIMARY KEY AUTOINCREMENT,
nbAddedSections INTEGER,
nbRemovedSections INTEGER,
time,
idmachine INTEGER NOT NULL CONSTRAINT fk2_idmachine REFERENCES machine (idmachine));
COMMIT;
