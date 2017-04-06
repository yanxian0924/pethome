/*
input:
    lat -> 30.252785
    lng -> -97.5
    sex -> m
    age -> 1
    approved -> 1 (after complete administration system)
*/
select pet.id, pet.name, pet.sex, age.name as age, pet.pic,
    (3959 * acos(cos(radians(30.252785)) * cos(radians(shelter.lat)) *
    cos(radians(shelter.lng) - radians(-97.5)) + sin(radians(30.252785)) *
    sin(radians(shelter.lat)))) as distance
from pet, shelter, age
where pet.age=age.id and pet.sex='m' and pet.age=1
HAVING distance < 20
ORDER BY distance;

select pet.name as pName, species.name as species,
    pet.sex, pet.breed, age.name as age, pet.size, pet.description, pet.pic,
    shelter.name as sName, shelter.email, shelter.phone, shelter.website,
    shelter.lat, shelter.lng
from pet, shelter, age, species
where pet.shelter=shelter.id
    and pet.age=age.id
    and pet.species=species.id
    and pet.id=1;
