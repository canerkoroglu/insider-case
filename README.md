<p align="center"><a href="https://useinsider.com" target="_blank"><img src="https://useinsider.com/assets/img/logo-old.png" width="200" alt="Insider Logo"></a></p>

## About Project

In this project, you simulate NBA first week games which shows “real-time” scores
and statistics. NBA fixture will be generated randomly. All matches
will start at the same time and will last 48 minutes and every minute will be simulated as 5
seconds which means whole match will end in 240 seconds.

## Key Features

- **Attack Count**
- **Total Score**
- **Player based assists**
- **Player based 2 or 3 points success rate.**

## Installation

1. Clone project
2. Install Composer in project folder (nba)

        composer install

        npm install
        or
        yarn install

        npm run build
        or
        yarn build
3. Copy `.env.example` values into `.env` file (create if not exists)
4. Create Laravel key

        php artisan key:generate

5. Run migration and import init data in project folder (nba)

        php artisan migrate
        php artisan db:seed --class=ImportInitialData
6. Run docker

        docker-compose up -d
   Please don't forget to check all containers up and running.\
   You can check it with `docker ps` command.
   ####
7. Open `http://localhost` in your browser
8. Generate Fixture on UI
9. Play Fixture UI, and enjoy!

## Other Feature Suggestions (TODO)
1. Use team power to calculate score
2. Use player power to calculate success rate
3. Change attack algorithm according to player playing positions
4. Add free throws
5. Add fouls and fouls out
6. Add substitutions

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
