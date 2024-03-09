# decomposer

List Composer 'vendor' directories on your system, how heavy they are, then you can select which ones you want to delete to free up space (NPM's npkill but for PHP Composer)

## Installation

```bash
composer global require martinshaw/decomposer
```

## Usage

```bash
decomposer # Opens the interactive UI
decomposer --all # Deletes all vendor directories without interaction
```

## Screenshot

![Screenshot](https://github.com/martinshaw/decomposer/blob/master/screenshot.png?raw=true)
