<?php

/**
 * Compress the HTML
 * @param type $html
 * @return type 
 */
function RainCompressHTML($html, $RainCompressConfig) {

    // Set PCRE recursion limit to sane value = STACKSIZE / 500
    // ini_set("pcre.recursion_limit", "524"); // 256KB stack. Win32 Apache
    ini_set("pcre.recursion_limit", "16777");  // 8MB stack. *nix
    $re = '%# Collapse whitespace everywhere but in blacklisted elements.
            (?>             # Match all whitespans other than single space.
            [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
            | \s{2,}        # or two or more consecutive-any-whitespace.
            ) # Note: The remaining regex consumes no text at all...
            (?=             # Ensure we are not in a blacklist tag.
            [^<]*+        # Either zero or more non-"<" {normal*}
            (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
            )*+           # Finish "unrolling-the-loop"
            (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
            | \z          # or end of file.
            )             # End alternation group.
            )  # If we made it here, we are not in a blacklist tag.
            %Six';
    $html = preg_replace($re, " ", $html);
    if ($html === null)
        exit("PCRE Error! File too big.\n");

    if ($RainCompressConfig['cache']) {

        if (!is_dir($RainCompressConfig['absolute_cache_dir']))
            mkdir($RainCompressConfig['absolute_cache_dir'], 0777, $recursive = true);

        file_put_contents($RainCompressConfig['cache_filepath'], $html);
    }

    return $html;
}