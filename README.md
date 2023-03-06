# Fullstack Challenge

## Instructions
Using Laravel and VueJS, create an application which shows the weather for a set of users.
- Clone this repository. 
- Once completed, send a link of the clone repository to interviewer and let them know how long the exercise took. 
- Update the frontend landing page to show a list of users and their current weather.
- Clicking a user opens a modal or screen which shows that users detailed weather report.
- Weather update should be no older than 1 hour.
- Internal API request(s) to retrieve weather data should take no longer than 500ms. Consider that external APIs could and will take longer than this from time to time and should be accounted for. 
- We are looking for attention to detail!
- Instructions are purposely left somewhat open-ended to allow the developer to make some of their own decisions on implementation and design. 
- This is not a designer test so the frontend does not have to look "good", but of course bonus points if you can make it look appealing. 

## Things to consider:
- Chose your own weather api such as https://openweathermap.org/api or https://www.weather.gov/documentation/services-web-api.
- Testability.
- Best practices.
- Design patterns.
- Availability of external APIs is not guaranteed and should not cause page to crash.
- Twenty randomized users are added via the seeder process, each having their own unique location (longitude and latitude).
- Redis is available (Docker service) if you wish to use it.
- Queues, workers, websockets could be useful.
- Feel free to use a frontend UI library such as PrimeVue, Vuetify, Bootstrap, Tailwind, etc. 
- Anything else you want to do to show off your coding chops!

## To run the local dev environment:

### API
- Navigate to `/api` folder
- Ensure version docker installed is active on host
- Copy .env.example: `cp .env.example .env`
- Start docker containers `docker compose up` (add `-d` to run detached)
- Connect to container to run commands: `docker exec -it fullstack-challenge-app-1 bash`
  - Make sure you are in the `/var/www/html` path
  - Install php dependencies: `composer install`
  - Setup app key: `php artisan key:generate`
  - Migrate database: `php artisan migrate` 
  - Seed database: `php artisan db:seed`
  - Run tests: `php artisan test`
- Visit api: `http://localhost`

### Frontend
- Navigate to `/frontend` folder
- Ensure nodejs v18 is active on host
- Install javascript dependencies: `npm install`
- Run frontend: `npm run dev`
- Visit frontend: `http://localhost:5173`


## Solution 
### Backend
- The 3rd party api key and url variables are added in .env.example file
- Added WeatherApi config file
- Created new command and added to schedule to fetch data for all users every hour. This solution is depends on some 
cases like maximum possible users count in our database. If we are going to consider an infinite count of users, we will 
need to get rig of this approach because of calling 3rd party api and database queries in foreach loop. Most probably we 
can use bulk download feature from openweathermap. 
- Used redis for caching api responses
- All api calls must be done trough `WeatherApiService` created in `app/Services` folder
- Application will fallow user's "create" and "update" events in User model and create or update user's weather forecast 
in database. It's done in order not to wait for the next cron job to see new or updated users weather data.

### Frontend
- Used Bootstrap 5 as css library
- Created services/ApiService.ts file to work with axios. In future all configurations with axios will be done there if needed
- In Vue components api calls will be done only trough corresponding repository created in `src/repositories` file,
like in current example `src/repositories/WeatherApiRepository.ts`
- Used backend pagination. From my point of view it's a must!
- All api calls are taking around 120-200 milliseconds
- Added .env.example file. It's needs to be copied to .env with `VITE_APP_API_URL` variable

```css
If I had more time I would add test coverage. Unfortunately tests are not done now