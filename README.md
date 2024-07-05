# PromptHub Coding Challenge

<img alt="PromptHub Logo" src="https://prompthub-assets.nyc3.cdn.digitaloceanspaces.com/prompthub-logo.png" style="height: 50px; width: auto;" />


## Summary

Your task is to implement a simple web application with a Laravel backend and Vue frontend that takes a user input and runs it through a two-step LLM prompt then render the output. User input will consist of two fields:

1. A question posed by the user
2. A modifier that will transform the output of the first prompt in some way defined by the user _(ex. translate to another language or rephrase to use a particular tone or accent)_.

This could be done all within a single prompt, but we'd like to see how you architect a simple system that chains two prompts together.

The boilerplate should include everything you need to get started, including all required dependencies and some example controllers and pages using Inertia. We'll provide links to the relevant documentation in
the [Resources](#resources) section of the readme.


## Requirements

- Create a simple UI that accepts the 2 text inputs from the user and then shows the LLM output at each stage _(the boilerplate project comes with TailwindCSS installed)_.
- Architect any migrations, models, and relationships you'll need to solve the challenge.
- The web app should be stateful, i.e. it should be specific to the currently logged-in user _(credentials will be seeded into the boilerplate project)_, and at least the prompt output should persist across page reloads.
- Implement a two-step prompt using the user inputs _(OpenAI SDK for Laravel is installed and we've provided you with a temporary OpenAI API key)_.
- Render the LLM output at each stage of the process.
- Write a Pest test to validate the happy path of at least one of your controller actions.
- When you're done, submit you work as a Pull Request on boilerplate repo.

## What We're Looking For

We just want to see how you approach problems, how you architect your solutions, and how you write your code. Feel free to be creative, as long as you complete the task at hand. Add clarifying comments if you feel a
particular implementation could be potentially confusing, but try to let your code speak for itself.

If you can't complete all tasks within a reasonable amount of time, do as much as you feel accurately conveys your skills as a developer and include a written explanation of how you would go about implementing the
remainder of the tasks if you had time in the PR description.


---

## Getting Started

### Prerequisites

- PHP ^8.2
- Composer
- Node/NPM

### Installation

1. Clone the boilerplate repo `git clone https://github.com/PromptHubUS/Coding-Challenge.git`
2. Install composer dependencies `composer install`
3. Install NPM dependencies `npm i`
4. Run migrations and seeders `art migrate:fresh --seed`

### Usage

1. Serve the backend `art serve`
2. Run the bundler `vite` or `npm run dev`
3. Login using test credentials `test@prompthub.us` and `password`

---

## Resources

The boilerplate includes all the necessary packages and configuration out of the box. Please utilize the resources listed below. There are also some optional resources installed, which you are free to use or not based on
your preferences.

#### Required

- [Laravel 11](https://laravel.com/docs/11.x) _(backend)_
- [Vue 3](https://vuejs.org/guide/introduction.html) _(frontend)_
- [InertiaJS](https://inertiajs.com/) _(passing data to frontend)_
- [TailwindCSS](https://tailwindcss.com/docs/utility-first) _(frontend styles)_
- [Ziggy](https://github.com/tighten/ziggy) _(reference backend routes on the frontend using `route()` helper)_
- [OpenAI-PHP/Laravel](https://github.com/openai-php/laravel) _(OpenAI SDK for PHP/Laravel)_
- [OpenAI API](https://platform.openai.com/docs/api-reference/chat/create) _(you're free to use either the legacy `/completions` endpoint or the new `/chat/completions` endpoint which supports the newer models)_
- [Pest](https://pestphp.com/docs/installation) _(testing framework)_
- [Vite](https://vitejs.dev/config/) _(frontend bundler - should be fully configured out of the box)_

#### Optional

- [Laravel-Actions](https://www.laravelactions.com/) _(single-file controllers, you're free to use regular Laravel controllers if you like)_
- [HeadlessUI](https://headlessui.com/v1/vue) _(UI components for Vue)_
- [Heroicons](https://heroicons.dev/) _(small collection of icons)_
- [Axios](https://axios-http.com/docs/example) _(HTTP client for non-inertia requests)_
- [Sass/SCSS](https://sass-lang.com/documentation/syntax/) _(CSS preprocessor with helpful syntax)_

### Notes

- Inertia renders pages based on their file name and position in the folder hierarchy under `resources/js/Pages`. Reference the Breeze controllers and pages as examples.
- You can define global Inertia props in the `HandleInertiaRequest` middleware.
- The .env file will be committed to the boilerplate repo in encrypted form. If you need to edit it, we can provide the key to decrypt it.
- Feel free to reach out if you have any problems or questions!
