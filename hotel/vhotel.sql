drop view StaffVIP;
drop view customerReview;


CREATE view customerReview as
SELECT reviewNo, roomTypeName, rating, content
FROM Review
ORDER BY reviewNo;

CREATE VIEW StaffVIP AS
SELECT hc.idNo,hc.name,hc.phoneno,hc.birthday, mi.points, mi.plevel, ld.discount
FROM Hotelcustomer hc, memberInfo mi, level_discount ld
WHERE  hc.idno = mi.idno and mi.plevel = ld.plevel
ORDER BY ld.plevel DESC;
