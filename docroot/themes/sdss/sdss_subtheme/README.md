# SDSS Subtheme

## Description

The SDSS sub-theme is a child theme of the Stanford Basic theme also located in the `sdss_profile` in the `ace-sdssgryphon` repo.

## Development

### Modifying CSS
The theme uses Yarn to compile the SCSS and all other assets into the distribution directory: `dist`. The CSS is compiled from the SCSS in the source directory: `src/scss`. To set up Yarn and compile:

1. Use the correct version of node:
    ```
    nvm use
    ```
2. Install all required packages:
    ```
    yarn install
    ```
3. Compile assets:
    ```
    yarn build
    ```

### Modifying JavaScript
The JS (JavaScript) is not compiled and located in the root `js` directory.
