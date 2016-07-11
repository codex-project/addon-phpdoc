## Overview

- Parse a

### Structures
Transforms SimpleXMLElement's JSON decoded array into a custom data structure.
- File > Entity (class, interface, trait)
- Entity > Methods > Tags
- Entity > Methods > Arguments > Tags
- Entity > Properties > Tags
- Entity > Constants


### Compiler 
- Read given (by who??) xml file from path
- Load xml file as SimpleXMLElement's, JSON encode/decodes it to array
- Loops trough the xml `$xml->file` array, creating a new `Structure/File[]` array
- Saves the `Structure/File[]` array for each file individually on the FS/cache?


### Structure
- it can transform a given arrays structure into something completely different
- it uses classes and sub-classes to represent arrays. 
- it can serialize and unserialize


### CompileStructureJob
- it can instantiate a `Compiler`
- it can setup a `Compiler` 

### Factory
- it can load Structures
- it can save Structures
- it can create new Structures
- it can combine Structures



### Stru


