
CREATE DATABASE IF NOT EXISTS dcraciun_magazin_online_db;
USE dcraciun_magazin_online_db;

CREATE TABLE IF NOT EXISTS USER (
    ID_user INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Nume_Utilizator VARCHAR(50) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    Parola VARCHAR(100) NOT NULL,
    Rol ENUM('Admin', 'Normal') NOT NULL,
    Data_Inregistrare DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS CATEGORIE (
    ID_categorie INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Nume_categorie VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS FURNIZOR (
    ID_Furnizor INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Nume_Companie VARCHAR(50) NOT NULL,
    Contact_Email VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS PRODUS (
    ID_Produs INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Nume_Produs VARCHAR(50) NOT NULL,
    Pret_Vanzare DECIMAL(10, 2) NOT NULL,
    ID_Categorie INT NOT NULL,
    ID_Furnizor INT NOT NULL,
    Imagine_URL VARCHAR(255),
    Pret_Vechi DECIMAL(10, 2) NULL,
    Procent_Reducere INT NULL,
    
    FOREIGN KEY (ID_Categorie) REFERENCES CATEGORIE(ID_categorie),
    FOREIGN KEY (ID_Furnizor) REFERENCES FURNIZOR(ID_Furnizor)
);

CREATE TABLE IF NOT EXISTS COS_CUMPARATURI (
    ID_Cos INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    ID_User INT NULL,
    FOREIGN KEY (ID_User) REFERENCES USER(ID_user)
);

CREATE TABLE IF NOT EXISTS Detalii_Cos (
    ID_Detaliu_Cos INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    ID_Cos INT NOT NULL,
    ID_Produs INT NOT NULL,
    Cantitate INT(6) NOT NULL,
    
    FOREIGN KEY (ID_Cos) REFERENCES COS_CUMPARATURI(ID_Cos),
    FOREIGN KEY (ID_Produs) REFERENCES PRODUS(ID_Produs)
);

CREATE TABLE IF NOT EXISTS COMANDA (
    ID_Comanda INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    ID_User INT NOT NULL,
    ID_Cos INT NOT NULL,
    Data_Comanda DATETIME NOT NULL,
    Status_Comanda ENUM('Nouă', 'În Procesare', 'Livrată', 'Anulată') NOT NULL,
    Total_Comanda DECIMAL(10, 2) NOT NULL,
    
    FOREIGN KEY (ID_User) REFERENCES USER(ID_user),
    FOREIGN KEY (ID_Cos) REFERENCES COS_CUMPARATURI(ID_Cos)
);

CREATE TABLE IF NOT EXISTS Comanda_Produs (
    ID_Comanda_Produs INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    ID_Comanda INT NOT NULL,
    ID_Produs INT NOT NULL,
    Cantitate INT(6) NOT NULL,
    Pret_Unitar_Vandut DECIMAL(10, 2) NOT NULL,
    
    FOREIGN KEY (ID_Comanda) REFERENCES COMANDA(ID_Comanda),
    FOREIGN KEY (ID_Produs) REFERENCES PRODUS(ID_Produs)
);

CREATE TABLE IF NOT EXISTS PROMOTIE (
    ID_Promotie INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Nume_Promotie VARCHAR(100) NOT NULL,
    Tip_Reducere DECIMAL(5, 2) NOT NULL,
    Data_Start DATE NOT NULL,
    Data_Stop DATE NOT NULL,
    ID_Categorie INT NULL,
    ID_User INT NULL,
    
    FOREIGN KEY (ID_Categorie) REFERENCES CATEGORIE(ID_categorie),
    FOREIGN KEY (ID_User) REFERENCES USER(ID_user)
);

CREATE TABLE IF NOT EXISTS NEWSLETTER (
    ID_Abonament INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Email_Abonat VARCHAR(100) NOT NULL UNIQUE,
    ID_User INT NULL,
    
    FOREIGN KEY (ID_User) REFERENCES USER(ID_user)
);

INSERT INTO CATEGORIE (ID_categorie, Nume_categorie) VALUES (1, 'Lactate si Deserturi');

INSERT INTO FURNIZOR (ID_Furnizor, Nume_Companie, Contact_Email) VALUES (1, 'Diversi Furnizori', 'contact@furnizori.ro');

INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Danette Budinca cu aroma de vanilie 125g', 
        3.86, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/had/h4a/9349742428190.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        4.82, 
        20
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Branza pentru paste 100g', 
        8.49, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h59/h07/9383606452254.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        9.99, 
        15
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Covalact de Tara Bulgarasi de branza si un strop de sare 5.5% grasime 180g', 
        7.69, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/hac/ha1/9278230069278.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        8.54, 
        10
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Covalact de Tara Smantana Ardeleneasca 15% grasime 370g', 
        11.99, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h67/h5e/9278217682974.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        12.62, 
        5
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Excellent Tiramisu 500g', 
        23.39, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h4f/h4b/9285890703390.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e62104979fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        26.58, 
        12
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'MEGA Smantana 12% grasime 900g', 
        10.49, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h8d/h86/9349706579998.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Danette Budinca cu ciocolata 125g', 
        3.86, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h7d/hef/9349740527646.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Cascaval clasic 325g', 
        21.63, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h79/h0d/9271097688094.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Activia Iaurt cu ovaz si nuci 125g', 
        2.99, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hd7/h52/9349677154334.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Branza de vaci textura grunjoasa 275g', 
        11.6, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hb4/h1d/9321447686174.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Cremosso Iaurt Stracciatella 5.4% grasime 125g', 
        3.33, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h77/hd7/9379065102366.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Muller Iaurt cu bilute de ciocolata 130g', 
        5.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h24/h2c/9349743869982.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Zuzu Lapte 3.5% grasime 500ml', 
        5.47, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h62/h00/9344274726942.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Kolios Iaurt grecesc 10% grasime 150g', 
        5.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h06/h26/9349664309278.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Napolact Lapte fara lactoza 3.5% grasime 1L', 
        11.99, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h0a/h66/9342310711326.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Napolact Lapte 1.5% grasime 1.5L', 
        15.99, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/he9/hcc/9342691016734.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Danone Delicios Iaurt cu biscuiti 125g', 
        2.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h88/h43/9349728206878.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Muller Smantana 24% grasime 375g', 
        14.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/ha2/he4/9349790269470.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Covalact de Tara Chefir usor 0.7% grasime 900g', 
        11.49, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/hee/hec/9278210474014.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Alpro Produs fermentat din soia, cu afine 150g', 
        4.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h4c/h3b/9349714444318.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Leerdammer Branza felii original 100g', 
        10.09, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h96/hc0/9349202804766.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Mozzarella fresh feliata 300g', 
        20.49, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/hb8/h82/9271113547806.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Olympus Iaurt de capra 4% grasime 150g', 
        4.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h6d/he7/9341902749726.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Stragghisto Iaurt cu specific grecesc 10% grasime 150g', 
        4.19, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h4e/h3c/9279761383454.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Covalact de Tara Sana 3.6% grasime 900g', 
        10.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h77/h81/9278227906590.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'President Branza cottage fara lactoza 180g', 
        8.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h8a/h63/9280617119774.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Laptaria cu caimac Cas zvantat 225g', 
        18.44, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hbe/hc5/9347772022814.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Olympus Feta de oaie si capra 200g', 
        20.29, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hf3/h02/9347540156446.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Familia Toneli Oua cod 1 marime L, 15 bucati', 
        21.49, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h73/h9d/9277935058974.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Delaco Mozzarella Gourmet 350g', 
        22.19, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hb4/h14/9388881805342.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Mirdatod Branza de vaci de Ibanesti 450g', 
        16.08, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h56/h76/9346101280798.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Cremosso Iaurt cu visine 3.6% grasime 125g', 
        3.33, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hc0/hf4/9379086991390.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Gusturi romanesti Lapte batut 2% grasime 330g', 
        4.06, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hd6/h9d/9349675712542.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Excellent Tiramisu clasic 2x90g', 
        11.99, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h7e/hd0/9349620269086.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Covalact de Tara Chefir 3.3% grasime 330g', 
        4.99, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h21/h35/9278220697630.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Gusturi romanesti Iaurt de baut 2% grasime 330g', 
        4.06, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h47/h60/9344270401566.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Danone Iaurt natural 3.5% grasime 130g', 
        1.41, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hf0/hda/9349657952286.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Mirdatod Urda de Ibanesti 400g', 
        12.92, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h62/h8d/9345405091870.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Branza pentru pizza 150g', 
        12.39, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h87/h44/9383644889118.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Napolact Unt de masa B 65% grasime 200g', 
        10.69, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/ha4/h36/9386638409758.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Branza topita triunghiuri Mixtett Classico 280g', 
        14.79, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/hf9/h8d/9271088840734.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Covalact de Tara Unt 65% grasime 200g', 
        11.5, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h68/h11/9278222860318.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Olympus Telemea light din lapte de vaca 150g', 
        10.09, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h06/hce/9279722127390.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Olympus Iaurt cu specific grecesc 10% grasime 900g', 
        20.89, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h2a/h1f/9349604737054.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Crema de branza proaspata cu verdeata 200g', 
        9.19, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hc6/h67/9383620968478.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Zuzu Lapte cu cacao si gust de ciocolata 1.5% grasime 450ml', 
        7.89, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h01/h81/9373945921566.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Casa Buna Smantana 12% grasime 370g', 
        6.0, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hae/h1f/9329297358878.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Branza extra cremoasa de vaca 400g', 
        29.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h76/hb8/9271120429086.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Velemea - Specialitate cu ulei de cocos si faina de migdale 150g', 
        10.29, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h18/hf4/9276926885918.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'YoPRO Budinca proteica cu gust de vanilie 180g', 
        8.74, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h24/h03/9262990786590.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Colectar Branza de bivolita rulou clasic 100g', 
        13.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hbd/ha6/9313469661214.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'YoPRO Budinca cu gust de caramel 180g', 
        8.74, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h21/h44/9261172817950.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Laptaria cu caimac Branza proaspata gingasa fara lactoza 1% grasime 300g', 
        18.44, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hba/h08/9402079510558.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'YoPRO Budinca ciocolata si alune 180g', 
        8.74, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/hce/h03/9261172097054.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'MEGA Cascaval din lapte de vaca 180g', 
        10.49, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h1f/h5b/9349448368158.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Branza topita cu smantana, triunghiuri 140g', 
        7.69, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h2f/h66/9271077568542.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Telemea cu verdeturi, din lapte de vaca 150g', 
        9.99, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h6b/h10/9271123968030.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Muller Iaurt in stil grecesc cu ananas 140g', 
        5.27, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hf0/he9/9349591826462.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Dorolact Cascaval Rucar 750g', 
        31.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h8c/hcb/9347173810206.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Gusturi romanesti Unt sarat 80% grasime 200g', 
        13.49, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h55/h38/9347994451998.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Telemea de oaie 200g', 
        14.89, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h6e/h34/9271137271838.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Delaco Ayran 2.1% grasime 250ml', 
        4.09, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h0d/h82/9263010971678.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Muller Smantana 24% grasime 150g', 
        7.69, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h56/h94/9349620989982.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Napolact Bio Lapte 1.5% grasime 1.5L', 
        18.19, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h06/he7/9342298062878.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Muller Iaurt cu bucati de mure si zmeura 500g', 
        9.89, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/he6/h12/9349678333982.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Mozzarella 300g', 
        18.89, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/h7c/h8b/9271100440606.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Excellent Profiterol 450g', 
        23.39, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/hd2/h48/9287769849886.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Lurpak Specialitate tartinabila nesarata 200g', 
        19.39, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/hcc/h84/9344633733150.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'MEGA Branza topita cu smantana 125g', 
        3.49, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h47/he8/9349674991646.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Branza topita cu smantana, felii, Pachet mare 280g', 
        14.39, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/ha7/hc2/9271054041118.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Hochland Branza topita cu cascaval, felii 140g', 
        7.69, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/hab/h76/9271136092190.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Philadelphia Crema de branza 300g', 
        20.69, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h4e/h96/9349581930526.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );
INSERT INTO PRODUS (Nume_Produs, Pret_Vanzare, ID_Categorie, ID_Furnizor, Imagine_URL, Pret_Vechi, Procent_Reducere)
    VALUES (
        'Danone Delicios Iaurt cu capsuni 125g', 
        2.59, 
        1, 
        1, 
        'https://static.mega-image.ro/medias/sys_master/products/h3f/hde/9349752717342.jpg?buildNumber=b6697d462f25ce1f5d74e6d8e6210497fe8992fdc6406154a56f2112f07f4fa4&imwidth=300', 
        NULL, 
        NULL
    );