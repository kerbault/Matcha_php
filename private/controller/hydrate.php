<?php

function fisherYatesShuffle(&$items, $seed)
{
    @mt_srand($seed);
    for ($i = count($items) - 1; $i > 0; $i--) {
        $j = @mt_rand(0, $i);
        $tmp = $items[$i];
        $items[$i] = $items[$j];
        $items[$j] = $tmp;
    }
}

function hydrateDb($seed)
{
    set_time_limit(100);
    $hydrate = new hydrate();
    $pictures = new pictures();
    $user = new user();

    $faker = Faker\Factory::create();
    $faker->seed($seed);

    srand(intval($seed));
    $users = 500 + (rand() % 501);

    echo "starting to register " . $users . " users !\n\n";

    $maleSet = explode("\n", file_get_contents('public/maleSet'));
    $femaleSet = explode("\n", file_get_contents('public/femaleSet'));
    fisherYatesShuffle($maleSet, $seed);
    fisherYatesShuffle($femaleSet, $seed);
    $m = 0;
    $f = 0;
    for ($i = 0; $i < $users; $i++) {
        echo "registering user..\n";
        $gender = $faker->numberBetween(2, 3);
        if ($gender == 2) {
            $fname = $faker->firstNameMale;
            $img = $maleSet[$m];
            $m++;
        } else {
            $fname = $faker->firstNameFemale;
            $img = $femaleSet[$f];
            $f++;
        }

        $lname = $faker->lastName;
        $birthDate = $faker->dateTimeBetween($startDate = '-30 years', $endDate = '-18 years', $timezone = null)->format('Y-m-d');
        $username = $faker->userName;
        $email = $faker->email;
        $pass = password_hash('root', PASSWORD_DEFAULT);
        $creation = $faker->dateTimeThisCentury->format('Y-m-d H:i:s');
        $key = '0';
        $orientation = $faker->numberBetween(1, 3);
        $position = $faker->latitude($min = 40, $max = 50) . "," . $faker->longitude($min = -3, $max = 9);
        $bio = $faker->realText(200, 2);
        $popularity = $faker->numberBetween(0, 500);

        $hydrate->registerUser($fname, $lname, $birthDate, $username, $email, $pass, $creation, $key, $gender, $orientation, $bio, $position, $popularity);
        $id = $user->fetchInfo($username, 'ID');
        $pictures->registerPicture($img, $id, true);
    }
}
