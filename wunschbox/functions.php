<?php

global $db;

function isMember($userId) {
    global $db;
    
    $sql = 'SELECT count(*) AS count FROM '.USER_GROUP_TABLE.' WHERE user_id = ' . $userId . ' AND group_id = 8';
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    
    if($row['count'] == 1) {
        return true;
    }
    
    return false;
}

function auth_generate_unique_cookie_string() {
    do {
            $t_cookie_string = crypto_generate_uri_safe_nonce( 64 );
    } while( !auth_is_cookie_string_unique( $t_cookie_string ) );

    return $t_cookie_string;
}

function auth_is_cookie_string_unique( $p_cookie_string ) {
    
    $mysqli = new mysqli('localhost', 'bugs', 'ffets2016!bugs', 'bugs');

    $sql = "SELECT COUNT(*) FROM {user} WHERE cookie_string='$p_cookie_string'";

    $result = $mysqli->query($sql);
    
    $row = $result->fetch_assoc();
    
    var_dump($row);
    die;

    $mysqli->close();
    
    db_param_push();
    $t_query = 'SELECT COUNT(*) FROM {user} WHERE cookie_string=' . db_param();
    $t_result = db_query( $t_query, array( $p_cookie_string ) );

    $t_count = db_result( $t_result );

    if( $t_count > 0 ) {
            return false;
    } else {
            return true;
    }
}

function crypto_generate_uri_safe_nonce( $p_minimum_length ) {
	$t_length_mod4 = $p_minimum_length % 4;
	$t_adjusted_length = $p_minimum_length + 4 - ($t_length_mod4 ? $t_length_mod4 : 4);
	$t_raw_bytes_required = ( $t_adjusted_length / 4 ) * 3;

	$t_random_bytes = crypto_generate_random_string( $t_raw_bytes_required, false );

	$t_base64_encoded = base64_encode( $t_random_bytes );
	$t_random_nonce = strtr( $t_base64_encoded, '+/', '-_' );
        
	return $t_random_nonce;
}

function crypto_generate_random_string( $p_bytes, $p_require_strong_generator = true ) {
	# First we attempt to use the secure PRNG provided by OpenSSL in PHP
	if( function_exists( 'openssl_random_pseudo_bytes' ) ) {
		$t_random_bytes = openssl_random_pseudo_bytes( $p_bytes, $t_strong );
		if( $t_random_bytes !== false ) {
			if( $p_require_strong_generator && $t_strong === true ) {
				$t_random_string = $t_random_bytes;
			} else if( !$p_require_strong_generator ) {
				$t_random_string = $t_random_bytes;
			}
		}
	}

	# Attempt to use mcrypt_create_iv - this is built into newer versions of php on windows
	# if the mcrypt extension is enabled on Linux, it takes random data from /dev/urandom
	if( !isset( $t_random_string ) ) {
		if( function_exists( 'mcrypt_create_iv' )
			&& ( version_compare( PHP_VERSION, '5.3.7' ) >= 0 )
		) {
			$t_random_bytes = mcrypt_create_iv( $p_bytes, MCRYPT_DEV_URANDOM );
			if( $t_random_bytes !== false && strlen( $t_random_bytes ) === $p_bytes ) {
				$t_random_string = $t_random_bytes;
			}
		}
	}

	# Next we try to use the /dev/urandom PRNG provided on Linux systems. This
	# is nowhere near as secure as /dev/random but it is still satisfactory for
	# the needs of MantisBT, especially given the fact that we don't want this
	# function to block while waiting for the system to generate more entropy.
	if( !isset( $t_random_string ) ) {
		
            $t_urandom_fp = @fopen( '/dev/urandom', 'rb' );
            if( $t_urandom_fp !== false ) {
                    $t_random_bytes = @fread( $t_urandom_fp, $p_bytes );
                    if( $t_random_bytes !== false ) {
                            $t_random_string = $t_random_bytes;
                    }
                    @fclose( $t_urandom_fp );
            }
	}

	# At this point we've run out of possibilities for generating randomness
	# from a strong source. Unless weak output is specifically allowed by the
	# $p_require_strong_generator argument, we should return null as we've
	# failed to generate randomness to a satisfactory security level.
	if( !isset( $t_random_string ) && $p_require_strong_generator ) {
		return null;
	}

	# As a last resort we have to fall back to using the insecure Mersenne
	# Twister pseudo random number generator provided in PHP. This DOES NOT
	# produce cryptographically secure randomness and thus the output of the
	# PRNG is easily guessable. In an attempt to make it harder to guess the
	# internal state of the PRNG, we salt the PRNG output with a known secret
	# and hash it.
	if( !isset( $t_random_string ) ) {
		$t_secret_key = 'prng' . '5r42ala9vKC/GK6WWe1LWNTj7iI5VejoCyP8fsHDthI=';
		$t_random_bytes = '';
		for( $i = 0; $i < $p_bytes; $i += 64 ) {
			$t_random_segment = '';
			for( $j = 0; $j < 64; $j++ ) {
				$t_random_segment .= base_convert( mt_rand(), 10, 36 );
			}
			$t_random_segment .= $i;
			$t_random_segment .= $t_secret_key;
			$t_random_bytes .= hash( 'whirlpool', $t_random_segment, true );
		}
		$t_random_string = substr( $t_random_bytes, 0, $p_bytes );
		if( $t_random_string === false ) {
			return null; # Unexpected error
		}
	}

	return $t_random_string;
}