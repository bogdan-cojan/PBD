////////////////////////////1,2,3////////////////////////////
CREATE TABLE client(sofer VARCHAR(10)  ,
			numar VARCHAR(7) CHECK(length(numar)=7) PRIMARY KEY, 
			marca VARCHAR(10) ,
			an_fabricatie INTEGER CHECK(an_fabricatie >=2000 AND an_fabricatie <= 2007));

CREATE TABLE service(sofer VARCHAR(10)  , 
			numar VARCHAR(7) CHECK(length(numar)=7) , 
			carburant INTEGER DEFAULT 60 CHECK(carburant<=60) , 
			data_alimentarii DATE , 
			kilometraj INTEGER , 
			CONSTRAINT constrangere FOREIGN KEY (numar) REFERENCES client(numar) ON DELETE CASCADE);

INSERT INTO client VALUES('Duma A.' , 'TMXXYY1' , 'Logan' , 2007);
INSERT INTO client VALUES('Ion M.' , 'TMXXYY2' , 'Renault' , 2006);
INSERT INTO client VALUES('Pop A.' , 'TMXXYY3' , 'Aro' , 2000);
INSERT INTO client VALUES('Popa M.' , 'TMXXYY4' , 'Cielo' , 2001);
INSERT INTO client VALUES('Marin O.' , 'TMXXYY5' , 'Matiz' , 2005);
INSERT INTO client VALUES('Ion M.' , 'TMXXYY6' , 'Ford' , 2003);
INSERT INTO client VALUES('Duma A.' , 'TMXXYY7' , 'BMW' , 2004);

INSERT INTO service VALUES('Duma A.' , 'TMXXYY1' , 40 , '10-Jan-2007' , 100);
INSERT INTO service VALUES('Ion M.' , 'TMXXYY2' , 34 , '01-Feb-2003' , 50);
INSERT INTO service VALUES('Pop A.' , 'TMXXYY3' , 35 , '07-Dec-2005' , 150);
INSERT INTO service VALUES('Popa M.' , 'TMXXYY4' , 55 , '11-Dec-2005' , 230);
INSERT INTO service VALUES('Marin O.' , 'TMXXYY5' , 34 , '14-May-2005' , 89);
INSERT INTO service VALUES('Duma A.' , 'TMXXYY1' , 40 , '10-Feb-2007' , 180);
INSERT INTO service VALUES('Ion M.' , 'TMXXYY6' , 40, '4-Mar-2006' , 56);
INSERT INTO service VALUES('Ion M.' , 'TMXXYY6' , 35 , '14-Feb-2007' , 50);
INSERT INTO service VALUES('Ion M.' , 'TMXXYY6' , 22 , '20-May-2007' , 40);
INSERT INTO service VALUES('Popa M.' , 'TMXXYY4' , 40 , '4-May-2006' , 100);
INSERT INTO service VALUES('Duma A.' , 'TMXXYY7' , 30 , '20-Mar-2004' , 40);
INSERT INTO service values('Ion M.' , 'TMXXYY6' , 50 , '23-Aug-2004' , 68);