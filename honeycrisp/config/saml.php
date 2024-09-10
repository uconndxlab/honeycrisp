<?php

return [
    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them signed or encrypted
    // Also will reject the messages if not strictly follow the SAML
    // standard: Destination, NameId, Conditions ... are validated too.
    // 'strict' => true,

    // Enable debug mode (to print errors)
    'debug' => true,

    // Set a BaseURL to be used instead of try to guess
    // the BaseURL of the view that process the SAML Message.
    // Ex. http://sp.example.com/
    //     http://example.com/sp/
    // 'baseurl' => null,

    // Service Provider Data that we are deploying
    'sp' => array (
        // Identifier of the SP entity  (must be a URI)
        'entityId' => env('SAML_SP_URL', 'http://i3.localhost'),
        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => array (
        //     // URL Location where the <Response> from the IdP will be returned
            'url' => env('SAML_ACS_URL', 'http://i3.localhost/saml'),
        //     // SAML protocol binding to be used when returning the <Response>
        //     // message.  SAML Toolkit supports for this endpoint the
        //     // HTTP-POST binding only
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Post',
        ),
        // If you need to specify requested attributes, set a
        // attributeConsumingService. nameFormat, attributeValue and
        // friendlyName can be omitted. Otherwise remove this section.
        // "attributeConsumingService"=> array(
        //         "serviceName" => "SP test",
        //         "serviceDescription" => "Test Service",
        //         "requestedAttributes" => array(
        //             array(
        //                 "name" => "",
        //                 "isRequired" => false,
        //                 "nameFormat" => "",
        //                 "friendlyName" => "",
        //                 "attributeValue" => ""
        //             )
        //         )
        // ),
        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        // 'singleLogoutService' => array (
        //     // URL Location where the <Response> from the IdP will be returned
        //     'url' => '',
        //     // SAML protocol binding to be used when returning the <Response>
        //     // message.  SAML Toolkit supports for this endpoint the
        //     // HTTP-Redirect binding only
        //     'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        // ),
        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:transient',

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        // 'x509cert' => '',
        // 'privateKey' => '',

        /*
         * Key rollover
         * If you plan to update the SP x509cert and privateKey
         * you can define here the new x509cert and it will be
         * published on the SP metadata so Identity Providers can
         * read them and get ready for rollover.
         */
        // 'x509certNew' => '',
    ),

    // Identity Provider Data that we want connect with our SP
    'idp' => array (
        // Identifier of the IdP entity  (must be a URI)
        'entityId' => env('SAML_ENTITY_ID', 'https://login.uconn.edu/cas/idp/metadata'),
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => array (
            // URL Target of the IdP where the SP will send the Authentication Request Message
            'url' => env('SAML_IDP_SSO_URL', 'https://login.uconn.edu/cas/idp/profile/SAML2/Redirect/SSO'),
            // SAML protocol binding to be used when returning the <Response>
            // message.  SAML Toolkit supports for this endpoint the
            // HTTP-Redirect binding only
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        // SLO endpoint info of the IdP.
        'singleLogoutService' => array (
            // URL Location of the IdP where the SP will send the SLO Request
            'url' => env('SAML_LOGOUT_URL', 'http://i3.localhost/saml/logout'),
            // URL location of the IdP where the SP will send the SLO Response (ResponseLocation)
            // if not set, url for the SLO Request will be used
            'responseUrl' => '',
            // SAML protocol binding to be used when returning the <Response>
            // message.  SAML Toolkit supports for this endpoint the
            // HTTP-Redirect binding only
            'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
        ),
        // Public x509 certificate of the IdP
        'x509cert' => '-----BEGIN CERTIFICATE-----
MIIDHjCCAgagAwIBAgIVALe1u1i7uvdpOmDc6Jn99FVZsuacMA0GCSqGSIb3DQEB
CwUAMBoxGDAWBgNVBAMMD2xvZ2luLnVjb25uLmVkdTAeFw0yMjA3MDQxOTE3NDda
Fw00MjA3MDQxOTE3NDdaMBoxGDAWBgNVBAMMD2xvZ2luLnVjb25uLmVkdTCCASIw
DQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBANQm/RSAuCTZql8PjZogZkg0Rup/
Vc7YrwdhGuoFmbxTjXbIn2dGDBGeh0Trc+MB04g+ZSn+cJv17TUSm0I5PdHVktby
m4hHpIT/VbRxR16UO519cxZ1i5JEGq1ITwgKiO+tfmOkw2s9PIUQmMhGia5M2ncw
fVWYK0OUUpQFWFX0pgJQnRA3KPmjyBPQcdIl7zOhkBH9gtxBHKSnHwEVIMWygCFE
UI5qQ0dBYI5psHFTpJq3iTrYUyZtFxEGbZVtah1MvXUyR971dfxywN4iT3Y9Tg7O
02rd/b20NNHjv1hzeG/RoepsLqrU83VhpgzKGGrQhBsbGdsSU17lzCO00rcCAwEA
AaNbMFkwHQYDVR0OBBYEFHUBYwBx79c2a4Wjhmoz58XF62NRMDgGA1UdEQQxMC+C
D2xvZ2luLnVjb25uLmVkdYYcbG9naW4udWNvbm4uZWR1L2lkcC9tZXRhZGF0YTAN
BgkqhkiG9w0BAQsFAAOCAQEAkwEoUOXkY43VrUHNpe951ut0vwec9qW9jr4rSL/S
jHyhPYbLRyx1O1QB2BvaifDuPdDhsJEAYvmmL3mgpHZPM2QBVaP5mnDwrbRRAZj3
xZbwzGF8v98I7pDMLpIzpIC4YN2y7EyMwK8Dk9gyswqepm4g52Jj9q/EsTDMzKTm
hYW7J3pSo/Z4czyLIcMBCfW4tFLi3CQzTt6xITh+w08wqVDFGa9NT3iBojOvvHqr
mLGVxrHVmcNvB3fLOV5kMlFGckvauUG5Ehe9Ep+sXdf8SPL+4V0JhGHVz677oTS0
TZM7SiZrAQZXngadT6+YQbU+Hsi5l0x2u9QcSPHbnnOBYw==
-----END CERTIFICATE-----',
        /*
         *  Instead of use the whole x509cert you can use a fingerprint in
         *  order to validate the SAMLResponse, but we don't recommend to use
         *  that method on production since is exploitable by a collision
         *  attack.
         *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it,
         *   or add for example the -sha256 , -sha384 or -sha512 parameter)
         *
         *  If a fingerprint is provided, then the certFingerprintAlgorithm is required in order to
         *  let the toolkit know which Algorithm was used. Possible values: sha1, sha256, sha384 or sha512
         *  'sha1' is the default value.
         */
        // 'certFingerprint' => '',
        // 'certFingerprintAlgorithm' => 'sha1',

        /* In some scenarios the IdP uses different certificates for
         * signing/encryption, or is under key rollover phase and more
         * than one certificate is published on IdP metadata.
         * In order to handle that the toolkit offers that parameter.
         * (when used, 'x509cert' and 'certFingerprint' values are
         * ignored).
         */
        // 'x509certMulti' => array(
        //      'signing' => array(
        //          0 => '<cert1-string>',
        //      ),
        //      'encryption' => array(
        //          0 => '<cert2-string>',
        //      )
        // ),
        // 'security' => [
        //     'authnRequestsSigned' => true,
        //     'allowRepeatAttributeName' => true,
        // ],
    ),
];