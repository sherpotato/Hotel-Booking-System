insert into HotelCustomer
  values ('1625372', '7788665372', 'Tiger Cao', '2000-12-12', 'female');
insert into HotelCustomer
  values ('3872095', '6048366738', 'Elephant Zhao', '1998-02-04', 'female');
insert into HotelCustomer
  values ('2947596', '7789666304', 'MiaoMiao Qian', '1999-06-12', 'male');
insert into HotelCustomer
  values ('4056732', '6676443333', 'Piggy Zhang', '1997-11-11', 'male');
insert into HotelBranch
  values (1,'5959 Student Union Blvd', 'Vancouver');
insert into HotelBranch
  values (2,'1628 Apple Road', 'Toronto');
insert into Level_DIscount
  values (1, 0.9);
insert into Level_DIscount
  values (2, 0.85);
insert into Level_DIscount
  values (3, 0.8);
insert into typeRate
  values (1, 'Sea View', 388);
insert into typeRate
  values (2, 'Lake View', 588);
insert into typeRate
  values (3, 'city View', 998);
insert into typeRate
  values (4, 'Sea View', 1298);
insert into typeInfo
  values ('King Room', 2, 'Sea View', 3);
insert into typeInfo
  values ('Executive Suite', 2, 'Provide all you need.', 3);
insert into typeInfo
  values ('Superior Double Room with Two Double Beds', 4, 'Whole Family', 3);
insert into typeInfo
  values ('Deluxe Twin Room', 4, 'Best choice for family.', 3);
insert into Payment
  values ('64736735654635625', 'Tiger Cao', '20211212', '1625372');
insert into Payment
  values ('34563456362562436', 'Elephant Zhao', '20240620', '3872095');
insert into Payment
  values ('34563456362562436', 'MiaoMiao Qian', '20201111', '2947596');
insert into Payment
  values ('34563456362562436', 'Piggy Zhang', '20210430', '4056732');
insert into OpeningHours
  values (8, 20);
insert into OpeningHours
  values (6,22);
insert into OpeningHours
  values (10, 24);
insert into OpeningHours
  values (12,23);
insert into OpeningHours
  values (18, 23);
insert into MemberInfo
  values (1625372, 100,1);
insert into MemberInfo
  values (3872095, 500, 1);
insert into MemberInfo
  values (2947596, 1200, 2);
insert into MemberInfo
  values (4056732, 2100, 3);
insert into Room
  values (101, 'no', 'King Room',1);
insert into Room
  values (102, 'yes', 'King Room',1);
insert into Room
  values (103, 'yes', 'King Room',1);
insert into Room
  values (201, 'yes', 'Executive Suite', 1);
insert into Room
  values (202, 'yes', 'Executive Suite', 1);
insert into Room
  values (203, 'yes', 'Executive Suite', 1);
insert into Room
  values (301, 'yes', 'Superior Double Room with Two Double Beds', 1);
insert into Room
  values (302, 'yes', 'Superior Double Room with Two Double Beds', 1);
insert into Room
  values (303, 'yes', 'Superior Double Room with Two Double Beds', 1);
insert into Room
  values (401, 'yes', 'Deluxe Twin Room', 1);
insert into Room
  values (402, 'yes', 'Deluxe Twin Room', 1);
insert into Room
  values (403, 'yes', 'Deluxe Twin Room', 1);
insert into Room
  values (101, 'no', 'King Room',2);
insert into Room
  values (102, 'yes', 'King Room',2);
insert into Room
  values (103, 'yes', 'King Room',2);
insert into Room
  values (201, 'yes', 'Executive Suite',2);
insert into Room
  values (202, 'yes', 'Executive Suite',2);
insert into Room
  values (203, 'yes', 'Executive Suite',2);
insert into Room
  values (301, 'yes', 'Superior Double Room with Two Double Beds', 2);
insert into Room
  values (302, 'yes', 'Superior Double Room with Two Double Beds', 2);
insert into Room
  values (303, 'yes', 'Superior Double Room with Two Double Beds', 2);
insert into Room
  values (401, 'yes', 'Deluxe Twin Room', 2);
insert into Room
  values (402, 'yes', 'Deluxe Twin Room', 2);
insert into Room
  values (403, 'yes', 'Deluxe Twin Room', 2);
insert into ServiceRate
  values ('SPA', 98);
insert into ServiceRate
  values ('In_Room Dining', 58);
insert into ServiceRate
  values ('Mini_Bar', 20);
insert into ServiceRate
  values ('Laundry', 30);
insert into RoomService
  values (1, 101, 'SPA');
insert into RoomService
  values (1, 101, 'Mini_Bar');
insert into RoomService
  values (1, 101, 'In_Room Dining');
insert into RoomService
  values (1, 201, 'In_Room Dining');
insert into RoomService
  values (1, 201, 'Mini_Bar');
insert into RoomService
  values (1, 201, 'Laundry');
insert into RoomService
  values (2, 302, 'SPA');
insert into RoomService
  values (1, 403, 'In_Room Dining');
insert into RoomService
  values (2, 201, 'Mini_Bar');
insert into RoomService
  values (1, 303, 'In_Room Dining');
insert into RoomService
  values (2, 102, 'Mini_Bar');
insert into RoomService
  values (2, 401, 'Laundry');
insert into Reservation
  values (1, 1, 'King Room', '1625372', '2018-11-12', '2018-11-13');
insert into Reservation
  values (2, 1, 'Executive Suite', '3872095', '2018-11-12', '2018-11-13');
insert into Reservation
  values (3, 1, 'Superior Double Room with Two Double Beds', '2947596', '2018-11-12', '2018-11-13');
insert into Reservation
  values (4, 1, 'Deluxe Twin Room', '4056732', '2018-11-12', '2018-11-13');
insert into Reservation
  values (5, 2, 'Executive Suite', '1625372', '2018-11-15', '2018-11-16');
insert into Reservation
  values (6, 2, 'Executive Suite', '3872095', '2018-11-15', '2018-11-16');
insert into Reservation
  values (7, 2, 'Executive Suite', '2947596', '2018-11-15', '2018-11-16');
insert into Reservation
  values (8, 2, 'Executive Suite', '4056732', '2018-11-15', '2018-11-16');
insert into OrderInfo
  values (1, 100, '1625372', '2018-11-12', '2018-11-13', 1, 101, 1, '2018-11-13', 0);
insert into OrderInfo
  values (2, 100, '3872095', '2018-11-12', '2018-11-13', 1, 201, 2, '2018-11-13', 0);
insert into OrderInfo
  values (3, 100, '2947596', '2018-11-12', '2018-11-13', 1, 301, 3, '2018-11-13', 0);
insert into OrderInfo
  values (4, 100, '4056732', '2018-11-12', '2018-11-13', 1, 401, 4, '2018-11-13', 0);
insert into OrderInfo
  values (5, 100, '1625372', '2018-11-15', '2018-11-16', 2, 201, 5, '2018-11-16', 0);
insert into OrderInfo
  values (6, 100, '3872095', '2018-11-15', '2018-11-16', 2, 202, 6, '2018-11-16', 0);
insert into OrderInfo
  values (7, 100, '2947596', '2018-11-15', '2018-11-16', 2, 203, 7, '2018-11-16', 0);
insert into Facility
  values ('Meeting Room', 8, 20, 1);
insert into Facility
  values ('Swimming Pool', 6, 22, 1);
insert into Facility
  values ('Meeting Room', 10, 24, 2);
insert into Facility
  values ('Swimming Pool', 6, 22, 2);
insert into Facility
  values ('Fitting Room', 6, 22, 1);
insert into Facility
  values ('KTV', 12, 23, 1);
insert into Facility
  values ('Fitting Room', 6, 22, 2);
insert into Facility
  values ('KTV', 18, 23, 2);
insert into Review
  values (1, '1625372', 'Good', 5, 'King Room');
insert into Review
  values (2, '3872095', 'Great', 5, 'Superior Double Room with Two Double Beds');
insert into Review
  values (3, '2947596', 'Great View', 4, 'Executive Suite');
insert into Review
  values (4, '4056732', 'Bed is too small', 2, 'Deluxe Twin Room');
insert into Review
  values (5, '1625372', 'Too Expensive', 3, 'Executive Suite');

  commit;
