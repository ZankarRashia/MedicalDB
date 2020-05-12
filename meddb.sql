DROP DATABASE IF EXISTS meddb;
CREATE DATABASE meddb;
USE meddb;

CREATE TABLE Doctors (
	NPI BIGINT NOT NULL UNIQUE,
	Name VARCHAR(80) NOT NULL,
    Password VARCHAR(80) NOT NULL,
	Work_phone VARCHAR(15) NOT NULL,
	Fax VARCHAR(15) NULL,
	Email VARCHAR(80) NOT NULL,
    Specialist ENUM('Yes', 'No') NOT NULL,
	Specialization VARCHAR(80) NOT NULL,
	PRIMARY KEY (NPI)
);
INSERT INTO Doctors VALUES (1234567890, 'James Jones', 'password', '(713) 456-1232', '(713) 446-7888', 'jjones@clinic.com', 'Yes', 'Orthopedic Surgeon');
INSERT INTO Doctors VALUES (1342523141, 'Samantha Levi', 'password', '(713) 342-1232', NULL, 'slevi@clinic.com', 'No', 'General Practitioner');
INSERT INTO Doctors VALUES (6734223145, 'Eugene Gray', 'password', '(713) 324-5887', NULL, 'egray@clinic.com', 'Yes', 'Oncologist');
INSERT INTO Doctors VALUES (5882941572, 'Kara Williams', 'password', '(512) 234-5568', '(512) 354-6650', 'kwilliams@clinic.com', 'Yes', 'Dermatologist');
INSERT INTO Doctors VALUES (3251567654, 'Ada Lovejoy', 'password', '(512) 112-2425', NULL , 'alovejoy@clinic.com', 'No', 'General Practitioner');
INSERT INTO Doctors VALUES (2196832962, 'Preston Gross', 'password', '(713) 233-0098', '(713) 443-3456', 'pgross@clinic.com', 'Yes', 'Endocrinologist');


CREATE TABLE Demographics (
	Demo_ID INT NOT NULL AUTO_INCREMENT,
	Has_insurance ENUM('Yes', 'No') NOT NULL,
    Age INT NOT NULL,
    Date_of_birth DATE NOT NULL,
    Sex ENUM('M', 'F', 'Other') NOT NULL,
    Ethnicity ENUM('Asian/Pacific Islander', 'African-American', 'Native American', 'White', 'Hispanic', 'Other') NOT NULL,
    Marital_status ENUM('Single', 'Married', 'Widowed', 'Divorced', 'Separated') NOT NULL,
    Home_phone VARCHAR(15) NOT NULL,
    Cell_phone VARCHAR(15) NULL,
    Work_phone VARCHAR(15) NULL,
    Email VARCHAR(80) NULL,
    Allergies VARCHAR(225) NULL,
    PRIMARY KEY (Demo_ID)
);
ALTER TABLE Demographics AUTO_INCREMENT = 1001;

DROP TRIGGER IF EXISTS `no_work_phone`;
DELIMITER $$
CREATE TRIGGER `no_work_phone` BEFORE INSERT ON `Demographics` 
FOR EACH ROW BEGIN
	IF (NEW.Work_phone IS NULL) THEN 
      SET NEW.Work_phone = NEW.Cell_phone;
	END IF;
END $$
DELIMITER ;

DROP TRIGGER IF EXISTS `minor_patient`;
DELIMITER $$
CREATE TRIGGER `minor_patient` BEFORE INSERT ON `Demographics`
FOR EACH ROW 
BEGIN
	IF NEW.age < 18 THEN
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='This patient is a minor and thus cannot set up an account';
	END IF;
END $$
DELIMITER ;

INSERT INTO Demographics VALUES (NULL, 'Yes', 75, '1945-01-01', 'M', 'Other', 'Widowed', '(713) 123-3645', '(713) 888-8888', NULL, 'takeshi@patient.com', 'Tree pollen');
INSERT INTO Demographics VALUES (NULL, 'Yes', 28, '1992-01-01', 'M', 'Hispanic', 'Single', '(713) 123-1241', NULL, NULL, 'hou@patient.com', 'Peanuts');
INSERT INTO Demographics VALUES (NULL, 'Yes', 31, '1989-01-01', 'F', 'White', 'Divorced', '(713) 123-5647', NULL, NULL, 'chantal@patient.com', NULL);
INSERT INTO Demographics VALUES (NULL, 'Yes', 58, '1962-01-01', 'F', 'Native American', 'Married', '(713) 123-7568', NULL, NULL, 'lucretia@patient.com', NULL);
INSERT INTO Demographics VALUES (NULL, 'Yes', 19, '2003-01-01', 'M', 'African-American', 'Single', '(713) 123-3465', NULL, NULL, 'jonathan@patient.com', NULL);
INSERT INTO Demographics VALUES (NULL, 'Yes', 20, '2000-01-01', 'M', 'Asian/Pacific Islander', 'Single', '(713) 123-7475', NULL, NULL, 'aleksei@patient.com', 'Cilantro');


CREATE TABLE Medical_history (
	Med_Hist_ID INT NOT NULL AUTO_INCREMENT,
    Prev_conditions VARCHAR(225) NULL,
    Past_surgeries VARCHAR(225) NULL,
    Blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NULL,
    Past_prescriptions VARCHAR(225) NULL,
    PRIMARY KEY (Med_Hist_ID)
);
ALTER TABLE Medical_history AUTO_INCREMENT = 301;
INSERT INTO Medical_history VALUES (NULL, NULL, 'Heart surgery', 'A+', NULL);
INSERT INTO Medical_history VALUES (NULL, 'Type 1 diabtic', NULL, 'O+', '30 ml insulin per day');
INSERT INTO Medical_history VALUES (NULL, 'Asthma', 'Appendix surgery', 'B-', NULL);

CREATE TABLE Family_history (
	Fam_Hist_ID INT NOT NULL AUTO_INCREMENT,
    Fam_History VARCHAR(225) NOT NULL,
    PRIMARY KEY (Fam_Hist_ID)
);
ALTER TABLE Family_history AUTO_INCREMENT = 501;
INSERT INTO Family_history VALUES (NULL, 'Mother had lung cancer');
INSERT INTO Family_history VALUES (NULL, 'Father had Type 2 diabetes');
INSERT INTO Family_history VALUES (NULL, 'Aunt on mother\'s side had celiac disease');
INSERT INTO Family_history VALUES (NULL, 'Grandfather had 4 strokes');

CREATE TABLE Nurses (
	NID INT NOT NULL UNIQUE,
    Password VARCHAR(80) NOT NULL,
    Name VARCHAR(80) NOT NULL,
    Email VARCHAR(80) NOT NULL,
    Job_description VARCHAR(225) NULL,
    PRIMARY KEY (NID)
);
INSERT INTO Nurses VALUES (34534, 'password', 'Samuel Norse', 'sam@nurse.com', 'Knows how to do IVs');
INSERT INTO Nurses VALUES (12456, 'password', 'Amanda Kubrick', 'amanda@nurse.com' , NULL);
INSERT INTO Nurses VALUES (15233, 'password', 'Justin Gruber', 'justin@nurse.com', NULL);
INSERT INTO Nurses VALUES (87680, 'password', 'Caitlin Gomez', 'caitlin@nurse.com', 'First year nurse');
INSERT INTO Nurses VALUES (24933, 'password', 'Ellen Grant', 'ellen@nurse.com', 'Twenty years of experience');

CREATE TABLE Patients (
	PID INT NOT NULL UNIQUE,
    	Password VARCHAR(80) NOT NULL,
	First_Name VARCHAR(80) NOT NULL,
	Last_Name VARCHAR(80) NOT NULL,
	Last_4_SSN INT NULL,
    Demographics_ID INT NOT NULL,
    Med_Hist_ID INT NOT NULL,
    Fam_Hist_ID INT NOT NULL,
    NID INT NULL,
	PRIMARY KEY (PID),
    FOREIGN KEY (Demographics_ID) REFERENCES Demographics(Demo_ID) ON DELETE CASCADE,
    FOREIGN KEY (Med_Hist_ID) REFERENCES Medical_history(Med_Hist_ID) ON DELETE CASCADE,
    FOREIGN KEY (Fam_Hist_ID) REFERENCES Family_history(Fam_Hist_ID) ON DELETE CASCADE,
	FOREIGN KEY (NID) REFERENCES Nurses(NID) ON DELETE CASCADE
);
INSERT INTO Patients VALUES (198664, 'password', 'Takeshi', 'Kitano', 9454, 1001, 303, 504, 24933);
INSERT INTO Patients VALUES (123124, 'password', 'Hou',  'Hsiao-hsien', 2314, 1002, 301, 502, 34534);
INSERT INTO Patients VALUES (125236, 'password', 'Chantal', 'Akerman', 8760, 1003, 301, 501, 87680);
INSERT INTO Patients VALUES (234622, 'password', 'Lucretia', 'Martel', 4534, 1004, 303, 503, 12456);
INSERT INTO Patients VALUES (558998, 'password', 'Jonathan', 'Glazer', 2603, 1005, 302, 502, 34534);
INSERT INTO Patients VALUES (325543, 'password', 'Aleksei', 'German', 8799, 1006, 302, 503, 12456);

DROP TRIGGER IF EXISTS `minor_patient`;
DELIMITER $$
CREATE TRIGGER `minor_patient` BEFORE INSERT ON `Patients`
FOR EACH ROW 
BEGIN
	DECLARE test_age INT;

	SELECT Age INTO test_age
	FROM Demographics WHERE Demo_ID=(SELECT Demographics_ID FROM Patients WHERE PID=NEW.PID);
	IF test_age < 18 THEN
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='This patient is a minor and thus cannot set up an account';
	END IF;
END $$
DELIMITER ;

CREATE TABLE Doctor_patient (
	PID INT NOT NULL,
    NPI BIGINT NOT NULL,
	PRIMARY KEY (PID, NPI),
	UNIQUE(PID, NPI),
    FOREIGN KEY (PID) REFERENCES Patients(PID) ON DELETE CASCADE,
    FOREIGN KEY (NPI) REFERENCES Doctors(NPI) ON DELETE CASCADE
);
INSERT INTO Doctor_patient VALUES (123124, 3251567654);
INSERT INTO Doctor_patient VALUES (125236, 1342523141);
INSERT INTO Doctor_patient VALUES (234622, 3251567654);
INSERT INTO Doctor_patient VALUES (558998, 3251567654);
INSERT INTO Doctor_patient VALUES (325543, 1342523141);
INSERT INTO Doctor_patient VALUES (198664, 3251567654);
INSERT INTO Doctor_patient VALUES (123124, 5882941572);
INSERT INTO Doctor_patient VALUES (125236, 2196832962);
INSERT INTO Doctor_patient VALUES (325543, 6734223145);
INSERT INTO Doctor_patient VALUES (198664, 6734223145);

CREATE TABLE Prescriptions (
	Prescript_ID INT NOT NULL AUTO_INCREMENT,
    Prescript_Name VARCHAR(100) NOT NULL,
    Dosage VARCHAR(225) NOT NULL,
    Refill ENUM('Y', 'N') NOT NULL,
    Prescribing_doc BIGINT NOT NULL,
    Patient INT NOT NULL,
    Expiration_date DATETIME NOT NULL,
    PRIMARY KEY (Prescript_ID),
    FOREIGN KEY (Prescribing_doc) REFERENCES Doctors(NPI) ON DELETE CASCADE,
    FOREIGN KEY (Patient) REFERENCES Patients(PID) ON DELETE CASCADE
);
ALTER TABLE Prescriptions AUTO_INCREMENT=701;

DROP TRIGGER IF EXISTS `duplicate_Script`;
DELIMITER $$
CREATE TRIGGER `duplicate_Script` BEFORE INSERT ON `Prescriptions` FOR EACH ROW 
BEGIN
	IF(EXISTS(SELECT 1 from Prescriptions WHERE
		Prescript_Name = NEW.Prescript_Name AND
		Patient = NEW.Patient)) THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Patient is already taking medication.';
	END IF;
END $$
DELIMITER ;

DROP TRIGGER IF EXISTS `out_of_date_Script`;
DELIMITER $$
CREATE TRIGGER `out_of_date_Script` BEFORE INSERT ON `Prescriptions` FOR EACH ROW 
BEGIN
	IF(NEW.Expiration_date < Now()) THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Prescription expiration date is in the past';
	END IF;
END $$
DELIMITER ;

INSERT INTO Prescriptions VALUES (NULL, 'Novalog', '20 ml per every other day', 'Y', 3251567654, 123124, '2020-12-12 10:00:00');
INSERT INTO Prescriptions VALUES (NULL, 'Amoxicillin', '2 pills every day', 'N', 3251567654, 234622, '2020-12-12 10:00:00');
INSERT INTO Prescriptions VALUES (NULL, 'Lung Steroid', '1 pill every day', 'N', 1342523141, 325543, '2020-12-12 10:00:00');

CREATE TABLE Clinics (
	Clinic_ID INT NOT NULL AUTO_INCREMENT,
	Clinic_name VARCHAR(50) NOT NULL,
	Office_phone VARCHAR(15) NOT NULL,
    Email VARCHAR(80) NULL,
	Address VARCHAR(200) NOT NULL,
	City VARCHAR(100) NOT NULL,
	State VARCHAR(2) NOT NULL,
	Zip VARCHAR(10) NOT NULL,
	PRIMARY KEY (Clinic_ID)
);
ALTER TABLE Clinics AUTO_INCREMENT=901;
INSERT INTO Clinics VALUES (NULL, 'West Clinic', '423-123-6758', NULL, '342 Main St', 'Houston','TX','77002-1234');
INSERT INTO Clinics VALUES (NULL, 'East Clinic', '423-231-5235', NULL, '1670 Richmond', 'Houston','TX','77076-6366');

CREATE TABLE Doctor_clinic (
	Clinic_ID INT NOT NULL,
    NPI BIGINT NOT NULL,
    PRIMARY KEY (Clinic_ID, NPI),
    FOREIGN KEY (Clinic_ID) REFERENCES Clinics(Clinic_ID) ON DELETE CASCADE,
    FOREIGN KEY (NPI) REFERENCES Doctors(NPI) ON DELETE CASCADE
);
INSERT INTO Doctor_clinic VALUES (901, 1234567890);
INSERT INTO Doctor_clinic VALUES (901, 1342523141);
INSERT INTO Doctor_clinic VALUES (901, 6734223145);
INSERT INTO Doctor_clinic VALUES (902, 5882941572);
INSERT INTO Doctor_clinic VALUES (902, 3251567654);
INSERT INTO Doctor_clinic VALUES (902, 2196832962);

CREATE TABLE Appointments (
	Appt_ID INT NOT NULL AUTO_INCREMENT,
	Has_approval ENUM('Yes', 'No') NOT NULL,
	Appointment_time DATETIME NOT NULL,
	Doctor_ID BIGINT NOT NULL,
	Patient_ID INT NOT NULL,
    Clinic_ID INT NOT NULL,
	PRIMARY KEY (Appt_ID),
	FOREIGN KEY (Doctor_ID) REFERENCES Doctors(NPI) ON DELETE CASCADE,
	FOREIGN KEY (Patient_ID) REFERENCES Patients(PID) ON DELETE CASCADE,
    FOREIGN KEY (Clinic_ID) REFERENCES Clinics(Clinic_ID) ON DELETE CASCADE
);
ALTER TABLE Appointments AUTO_INCREMENT=100001;

DROP TRIGGER IF EXISTS `duplicateAppointment`;
DELIMITER $$
CREATE TRIGGER `duplicateAppointment` BEFORE INSERT ON `Appointments` FOR EACH ROW BEGIN
	IF(EXISTS(SELECT 1 FROM Appointments WHERE
		Appointment_time = NEW.Appointment_time AND
		Doctor_ID = NEW.Doctor_ID AND
		Patient_ID = NEW.Patient_ID)) THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Appointment already exists on this data and time.';
	END IF;
END $$
DELIMITER ;

DROP TRIGGER IF EXISTS `no_insurance`;
DELIMITER $$
CREATE TRIGGER `no_insurance` BEFORE INSERT ON `Appointments`
FOR EACH ROW 
BEGIN
	DECLARE test ENUM('Yes','No');

	SELECT Has_insurance INTO test
	FROM Demographics WHERE Demo_ID=(SELECT Demographics_ID FROM Patients WHERE PID=NEW.Patient_ID);

	IF test = 'No' THEN
	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='This patient cannot schedule an appointment without insurance.';
	END IF;
END $$
DELIMITER ;

INSERT INTO Appointments VALUES (NULL, 'Yes', '2020-01-01 10:00:00', 3251567654, 123124, 901);
INSERT INTO Appointments VALUES (NULL, 'Yes', '2020-03-24 9:30:00', 2196832962, 125236, 901);
INSERT INTO Appointments VALUES (NULL, 'Yes', '2020-02-28 3:30:00', 6734223145, 325543, 902);
INSERT INTO Appointments VALUES (NULL, 'Yes', '2020-01-01 08:00:00', 1234567890, 123124, 901);

CREATE TABLE Actions (
	Action_ID INT NOT NULL AUTO_INCREMENT,
	User_Type ENUM('Admin','Patient','Nurse','Doctor') NOT NULL,
	User_ID BIGINT NOT NULL,
	Action_Type ENUM('Logged In', 'Logged Out', 'Created New User', 'Modified Record', 'Scheduled Appointment', 'Prescription Written') NOT NULL,
	Record_Modified_ID BIGINT NULL,
	Action_Time DATETIME NOT NULL,
	PRIMARY KEY (Action_ID)
);

CREATE TABLE Admin (
	Admin_ID INT NOT NULL AUTO_INCREMENT,
	Name VARCHAR(100) NOT NULL,
	Email VARCHAR(80) NOT NULL,
	Password VARCHAR(80) NOT NULL,
	PRIMARY KEY (Admin_ID)
);
ALTER TABLE Admin AUTO_INCREMENT=1101;
INSERT INTO Admin VALUES (NULL, 'Tom Swiney', 'admin@admin.com', 'password')