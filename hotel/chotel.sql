drop table Review;
drop table Facility;
drop table OrderInfo;
drop table Reservation;
drop table RoomService;
drop table ServiceRate;
drop table Room;
drop table OpeningHours;
drop table MemberInfo;
drop table Payment;
drop table typeInfo;
drop table typeRate;
drop table Level_Discount;
drop table HotelBranch;
drop table HotelCustomer;

// Create new table...
  CREATE TABLE HotelCustomer (
    idNo VARCHAR(30),
    phoneNo VARCHAR(30),
    name VARCHAR(30),
    birthday VARCHAR(30),
    gender VARCHAR(30),
    PRIMARY KEY(idNo),
    UNIQUE (phoneNo, name));
  CREATE TABLE HotelBranch (
    bid INTEGER,
    address VARCHAR(30),
    city VARCHAR(30),
    PRIMARY KEY(bid),
    UNIQUE(address, city));
  CREATE TABLE Level_Discount (
    plevel INTEGER,
    discount float(10),
    PRIMARY KEY(plevel));
  CREATE TABLE typeRate (
    capacity INTEGER,
    feature VARCHAR(30),
    price float(10),
    PRIMARY KEY (capacity));
  CREATE TABLE typeInfo(
    roomTypeName VARCHAR(50),
    capacity INTEGER,
    feature VARCHAR(30),
    availability INTEGER,
    PRIMARY KEY (roomTypeName),
    FOREIGN KEY (capacity) REFERENCES typeRate(capacity)
    ON DELETE CASCADE);
  CREATE TABLE Payment (
    cardNo VARCHAR(30),
    cardName VARCHAR(30),
    expDate VARCHAR(30),
    idNo VARCHAR(30),
    PRIMARY KEY (cardNo, idNo),
    FOREIGN KEY (idNo) REFERENCES HotelCustomer(idNo)
    ON DELETE CASCADE);
  CREATE TABLE OpeningHours (
    fromTime INTEGER,
    toTime INTEGER,
    PRIMARY KEY(fromTime, toTime));
  CREATE TABLE MemberInfo(
    idNo VARCHAR(30),
    points INTEGER,
    plevel INTEGER,
    PRIMARY KEY(idNo),
    FOREIGN KEY(idNo) REFERENCES HotelCustomer(idNo));
  CREATE TABLE Room (
    roomNo INTEGER,
    availability VARCHAR(5),
    roomTypeName VARCHAR(50),
    bid INTEGER,
    PRIMARY KEY (roomNo,bid),
    FOREIGN KEY (roomTypeName) REFERENCES typeInfo(roomTypeName)
    ON DELETE CASCADE,
    FOREIGN KEY (bid) REFERENCES HotelBranch(bid)
    ON DELETE CASCADE);
  CREATE TABLE ServiceRate (
    roomServiceName VARCHAR(30),
    roomServicePrice float(10),
    PRIMARY KEY (roomServiceName));
  CREATE TABLE RoomService (
    bid INTEGER,
    roomNo INTEGER,
    roomServiceName VARCHAR(30),
    PRIMARY KEY (bid,roomNo,roomServiceName),
    FOREIGN KEY (bid,roomNo) REFERENCES Room(bid,roomNo)
    ON DELETE CASCADE,
    FOREIGN KEY (roomServiceName) REFERENCES ServiceRate(roomServiceName)
    ON DELETE CASCADE);
  CREATE TABLE Reservation (
    confNo INTEGER,
    bid INTEGER,
    roomTypeName VARCHAR(50),
    idNo VARCHAR(30),
    fromDate date,
    toDate date,
    PRIMARY KEY(confNo),
    FOREIGN KEY (bid) REFERENCES HotelBranch(bid)
    ON DELETE CASCADE,
    FOREIGN KEY (roomTypeName) REFERENCES typeInfo(roomTypeName),
    FOREIGN KEY (idNo) REFERENCES HotelCustomer(idNo));
  CREATE TABLE OrderInfo (
    orderNo INTEGER,
    deposit INTEGER,
    idNo VARCHAR(30),
    fromDate date,
    toDate date,
    bid INTEGER,
    roomNo INTEGER,
    confNo INTEGER,
    cheakOutTime VARCHAR(30),
    cost FLOAT,
    PRIMARY KEY(orderNo),
    UNIQUE(confNo),
    FOREIGN KEY (idNo) REFERENCES HotelCustomer(idNo),
    FOREIGN KEY (bid,roomNo) REFERENCES Room(bid,roomNo),
    FOREIGN KEY (confNo) REFERENCES Reservation(confNo));
  CREATE TABLE Facility (
    facilityName VARCHAR(30),
    fromTime INTEGER,
    toTime INTEGER,
    bid INTEGER,
    PRIMARY KEY(bid,facilityname),
    FOREIGN KEY(fromTime, toTime) REFERENCES OpeningHours(fromTime, toTime),
    FOREIGN KEY(bid) REFERENCES HotelBranch(bid)
    ON DELETE CASCADE);
  CREATE TABLE Review (
    reviewNo INTEGER,
    idNo VARCHAR(30),
    content VARCHAR(200),
    rating INTEGER,
    roomTypeName VARCHAR(50),
    PRIMARY KEY(reviewNo),
    FOREIGN KEY (idNo) REFERENCES HotelCustomer(idNo),
    FOREIGN KEY (roomTypeName) REFERENCES typeInfo(roomTypeName)
    ON DELETE CASCADE);

commit;
