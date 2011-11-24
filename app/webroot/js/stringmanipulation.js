// Removes intonation plus final Sigma from the supplied string.
function purgeIntonation(name) {

    return name
        .replace( /ά/gi, 'Α' )
        .replace( /έ/gi, 'Ε' )
        .replace( /ή/gi, 'Η' )
        .replace( /[ίϊΐ]/gi, 'Ι' )
        .replace( /ό/gi, 'Ο' )
        .replace( /[ύϋΰ]/gi, 'Υ' )
        .replace( /ώ/gi, 'Ω' )
        .replace( /ς/gi, 'Σ' )
}

// Ignores nominative case of parameter [name] and matches it against
// parameter [target] as a regular expression.
function matchesMaimed(name, target) {

    maimed = name.replace(
        /(ΟΣ|ΗΣ|ΑΣ|ΟΥ|ΕΩΣ|ΟΙ|ΙΩΝ|ΩΝ|ΕΣ|Α|Η|ΟΝ|Ο|Ι)(\s|$)/gi, '.*' );

    regex = new RegExp( maimed, "gi" );

    return target.search( regex );
}
