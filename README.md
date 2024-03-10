# decomposer

List Composer 'vendor' directories on your system, how heavy they are, then you can select which ones you want to delete to free up space (NPM's npkill but for PHP Composer)

## Screenshot

![Screenshot](https://github.com/martinshaw/decomposer/blob/master/screenshot.png?raw=true)

## Usage

```bash
decomposer # Opens the interactive UI
decomposer --all # Deletes all vendor directories without interaction
```

## Installation

```bash
composer global require martinshaw/decomposer
```

This will install the `decomposer` command in your global composer bin directory, which is usually `~/.composer/vendor/bin` or `~/.config/composer/vendor/bin` on Unix systems and `%APPDATA%\Composer\vendor\bin` on Windows.

To access the `decomposer` command from anywhere, you need to add the global composer bin directory to your system's PATH environment variable.

### On Windows

1. Open the Control Panel.
2. Click on System and Security.
3. Click on System.
4. Click on Advanced system settings.
5. Click on Environment Variables.
6. Under System variables, find and select the `Path` variable, then click on Edit.
7. Click on New and add the path to your global composer bin directory.
8. Click on OK on all windows to apply the changes.

### On Linux and macOS

Depending on if your global composer bin directory is `~/.composer/vendor/bin` or `~/.config/composer/vendor/bin`, you need to add the following line to your `~/.bashrc`, `~/.zshrc`, or `~/.profile` file:

```bash
export PATH="$PATH:$HOME/.composer/vendor/bin"
```

or

```bash
export PATH="$PATH:$HOME/.config/composer/vendor/bin"
```

Then, run the following command to apply the changes:

```bash 
source ~/.bashrc
```

or `source ~/.zshrc` or `source ~/.profile` depending on which file you added the line to.