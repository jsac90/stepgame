use stepgame;

select * from weapon;

insert into weapon(weapon_name, weapon_power) values ('A Big Sttick',1);
commit;

select * from armor;

insert into armor(armor_name, armor_power) values ('Workout Clothes',1);
commit;

select * from game_character;

select * from cust_data;

select * from entrance;

update game_character set weapon_id = 1, armor_id = 1;
commit;
