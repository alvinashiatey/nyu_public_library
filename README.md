# NYU Digital Library Project

This projects uses [Kirby CMS](https://getkirby.com/) as a headless CMS and [Astro](https://astro.build/) as a frontend framework.

To spin this up locally, you'll need to have [Docker](https://www.docker.com/) installed.

1. Clone this repository
2. Run `docker-compose up` in the root of the repository. This will start up the headless cms container which is running Kirby CMS.
3. Visit `http://localhost:3000/panel` in your browser to access the cms.
4. cd into the `frontend` directory and run `npm install` to install the dependencies.
5. Run `npm run dev` to start the frontend server.
