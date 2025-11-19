base
RUN docker-php-ext-configure gd --with-freetype --with-jpeg     && docker-php-ext-configure imap --with-imap --with-imap-ssl     && docker-php-ext-install -j$(nproc)     pdo_mysql     mysqli     mbstring     exif     pcntl     bcmath     gd     xml     zip     soap     intl     imap
55s
checking for inttypes.h... 
yes
checking for stdint.h... 
yes
checking for strings.h... 
yes
checking for sys/stat.h... 
yes
checking for sys/types.h... 
yes
checking for unistd.h... 
yes
checking for dlfcn.h... 
yes
checking the maximum length of command line arguments... 
98304
checking command to parse /usr/bin/nm -B output from cc object... 
ok
checking for objdir... 
.libs
checking for ar... 
ar
checking for ranlib... 
ranlib
checking for strip... 
strip
checking if cc supports -fno-rtti -fno-exceptions... 
no
checking for cc option to produce PIC... 
-fPIC
checking if cc PIC flag -fPIC works... 
yes
checking if cc static flag -static works... 
yes
checking if cc supports -c -o file.o... 
yes
checking whether the cc linker (/usr/x86_64-alpine-linux-musl/bin/ld -m elf_x86_64) supports shared libraries... 
yes
checking whether -lc should be explicitly linked in... 
no
checking dynamic linker characteristics... 
GNU/Linux ld.so
checking how to hardcode library paths into programs... 
immediate
checking whether stripping libraries is possible... 
yes
checking if libtool supports shared libraries... yes
checking whether to build shared libraries... 
yes
checking whether to build static libraries... 
no
creating libtool
appending configuration tag "CXX" to libtool
configure: patching config.h.in
configure: creating ./config.status
config.status: creating config.h
/bin/sh /usr/src/php/ext/pcntl/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/pcntl -I/usr/src/php/ext/pcntl/include -I/usr/src/php/ext/pcntl/main -I/usr/src/php/ext/pcntl -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DHAVE_STRUCT_SIGINFO_T -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/pcntl/pcntl.c -o pcntl.lo  -MMD -MF pcntl.dep -MT pcntl.lo
/bin/sh /usr/src/php/ext/pcntl/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/pcntl -I/usr/src/php/ext/pcntl/include -I/usr/src/php/ext/pcntl/main -I/usr/src/php/ext/pcntl -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DHAVE_STRUCT_SIGINFO_T -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/pcntl/php_signal.c -o php_signal.lo  -MMD -MF php_signal.dep -MT php_signal.lo
mkdir .libs
mkdir .libs
mkdir: can't create directory '.libs': File exists
 cc -I. -I/usr/src/php/ext/pcntl -I/usr/src/php/ext/pcntl/include -I/usr/src/php/ext/pcntl/main -I/usr/src/php/ext/pcntl -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DHAVE_STRUCT_SIGINFO_T -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/pcntl/php_signal.c -MMD -MF php_signal.dep -MT php_signal.lo  -fPIC -DPIC -o .libs/php_signal.o
 cc -I. -I/usr/src/php/ext/pcntl -I/usr/src/php/ext/pcntl/include -I/usr/src/php/ext/pcntl/main -I/usr/src/php/ext/pcntl -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DHAVE_STRUCT_SIGINFO_T -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/pcntl/pcntl.c -MMD -MF pcntl.dep -MT pcntl.lo  -fPIC -DPIC -o .libs/pcntl.o
/bin/sh /usr/src/php/ext/pcntl/libtool --tag=CC --mode=link cc -shared -I/usr/src/php/ext/pcntl/include -I/usr/src/php/ext/pcntl/main -I/usr/src/php/ext/pcntl -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE  -Wl,-O1 -pie  -o pcntl.la -export-dynamic -avoid-version -prefer-pic -module -rpath /usr/src/php/ext/pcntl/modules  pcntl.lo php_signal.lo 
cc -shared  .libs/pcntl.o .libs/php_signal.o   -Wl,-O1 -Wl,-soname -Wl,pcntl.so -o .libs/pcntl.so
creating pcntl.la
(cd .libs && rm -f pcntl.la && ln -s ../pcntl.la pcntl.la)
/bin/sh /usr/src/php/ext/pcntl/libtool --tag=CC --mode=install cp ./pcntl.la /usr/src/php/ext/pcntl/modules
cp ./.libs/pcntl.so /usr/src/php/ext/pcntl/modules/pcntl.so
cp ./.libs/pcntl.lai /usr/src/php/ext/pcntl/modules/pcntl.la
PATH="$PATH:/sbin" ldconfig -n /usr/src/php/ext/pcntl/modules
----------------------------------------------------------------------
Libraries have been installed in:
   /usr/src/php/ext/pcntl/modules
If you ever happen to want to link against installed libraries
in a given directory, LIBDIR, you must either use libtool, and
specify the full pathname of the library, or use the `-LLIBDIR'
flag during linking and do at least one of the following:
   - add LIBDIR to the `LD_LIBRARY_PATH' environment variable
     during execution
   - add LIBDIR to the `LD_RUN_PATH' environment variable
     during linking
   - use the `-Wl,--rpath -Wl,LIBDIR' linker flag
See any operating system documentation about shared libraries for
more information, such as the ld(1) and ld.so(8) manual pages.
----------------------------------------------------------------------
Build complete.
Don't forget to run 'make test'.
+ strip --strip-all modules/pcntl.so
Installing shared extensions:     /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
find . -name \*.gcno -o -name \*.gcda | xargs rm -f
find . -name \*.lo -o -name \*.o -o -name \*.dep | xargs rm -f
find . -name \*.la -o -name \*.a | xargs rm -f
find . -name \*.so | xargs rm -f
find . -name .libs -a -type d|xargs rm -rf
rm -f libphp.la      modules/* libs/*
rm -f ext/opcache/jit/zend_jit_x86.c
rm -f ext/opcache/jit/zend_jit_arm64.c
rm -f ext/opcache/minilua
Configuring for:
PHP Api Version:         20220829
Zend Module Api No:      20220829
Zend Extension Api No:   420220829
checking for grep that handles long lines and -e... 
/bin/grep
checking for egrep... 
/bin/grep -E
checking for a sed that does not truncate output... 
/bin/sed
checking for pkg-config... 
/usr/bin/pkg-config
checking pkg-config is at least version 0.9.0... 
yes
checking for cc... 
cc
checking whether the C compiler works... 
yes
checking for C compiler default output file name... a.out
checking for suffix of executables... 
checking whether we are cross compiling... 
no
checking for suffix of object files... 
o
checking whether the compiler supports GNU C... 
yes
checking whether cc accepts -g... 
yes
checking for cc option to enable C11 features... 
none needed
checking how to run the C preprocessor... 
cc -E
checking for egrep -e... 
(cached) 
/bin/grep -E
checking for icc... 
no
checking for suncc... 
no
checking for system library directory... lib
checking if compiler supports -Wl,-rpath,... 
yes
checking build system type... 
x86_64-pc-linux-musl
checking host system type... 
x86_64-pc-linux-musl
checking target system type... 
x86_64-pc-linux-musl
checking for PHP prefix... /usr/local
checking for PHP includes... 
-I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib
checking for PHP extension directory... 
/usr/local/lib/php/extensions/no-debug-non-zts-20220829
checking for PHP installed headers prefix... 
/usr/local/include/php
checking if debug is enabled... 
no
checking if zts is enabled... 
no
checking for gawk... 
no
checking for nawk... 
no
checking for awk... 
awk
checking if awk is broken... 
no
checking whether to enable bc style precision math functions... 
yes, shared
checking for a sed that does not truncate output... 
/bin/sed
checking for ld used by cc... 
/usr/x86_64-alpine-linux-musl/bin/ld
checking if the linker (/usr/x86_64-alpine-linux-musl/bin/ld) is GNU ld... 
yes
checking for /usr/x86_64-alpine-linux-musl/bin/ld option to reload object files... 
-r
checking for BSD-compatible nm... 
/usr/bin/nm -B
checking whether ln -s works... 
yes
checking how to recognize dependent libraries... 
pass_all
checking for stdio.h... 
yes
checking for stdlib.h... 
yes
checking for string.h... 
yes
checking for inttypes.h... 
yes
checking for stdint.h... 
yes
checking for strings.h... 
yes
checking for sys/stat.h... 
yes
checking for sys/types.h... 
yes
checking for unistd.h... 
yes
checking for dlfcn.h... 
yes
checking the maximum length of command line arguments... 
98304
checking command to parse /usr/bin/nm -B output from cc object... 
ok
checking for objdir... 
.libs
checking for ar... 
ar
checking for ranlib... 
ranlib
checking for strip... 
strip
checking if cc supports -fno-rtti -fno-exceptions... 
no
checking for cc option to produce PIC... 
-fPIC
checking if cc PIC flag -fPIC works... 
yes
checking if cc static flag -static works... 
yes
checking if cc supports -c -o file.o... 
yes
checking whether the cc linker (/usr/x86_64-alpine-linux-musl/bin/ld -m elf_x86_64) supports shared libraries... 
yes
checking whether -lc should be explicitly linked in... 
no
checking dynamic linker characteristics... 
GNU/Linux ld.so
checking how to hardcode library paths into programs... 
immediate
checking whether stripping libraries is possible... 
yes
checking if libtool supports shared libraries... yes
checking whether to build shared libraries... 
yes
checking whether to build static libraries... 
no
creating libtool
appending configuration tag "CXX" to libtool
configure: patching config.h.in
configure: creating ./config.status
config.status: creating config.h
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/bcmath.c -o bcmath.lo  -MMD -MF bcmath.dep -MT bcmath.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/add.c -o libbcmath/src/add.lo  -MMD -MF libbcmath/src/add.dep -MT libbcmath/src/add.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/div.c -o libbcmath/src/div.lo  -MMD -MF libbcmath/src/div.dep -MT libbcmath/src/div.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/init.c -o libbcmath/src/init.lo  -MMD -MF libbcmath/src/init.dep -MT libbcmath/src/init.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/neg.c -o libbcmath/src/neg.lo  -MMD -MF libbcmath/src/neg.dep -MT libbcmath/src/neg.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/raisemod.c -o libbcmath/src/raisemod.lo  -MMD -MF libbcmath/src/raisemod.dep -MT libbcmath/src/raisemod.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/sub.c -o libbcmath/src/sub.lo  -MMD -MF libbcmath/src/sub.dep -MT libbcmath/src/sub.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/compare.c -o libbcmath/src/compare.lo  -MMD -MF libbcmath/src/compare.dep -MT libbcmath/src/compare.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/divmod.c -o libbcmath/src/divmod.lo  -MMD -MF libbcmath/src/divmod.dep -MT libbcmath/src/divmod.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/int2num.c -o libbcmath/src/int2num.lo  -MMD -MF libbcmath/src/int2num.dep -MT libbcmath/src/int2num.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/num2long.c -o libbcmath/src/num2long.lo  -MMD -MF libbcmath/src/num2long.dep -MT libbcmath/src/num2long.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/output.c -o libbcmath/src/output.lo  -MMD -MF libbcmath/src/output.dep -MT libbcmath/src/output.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/recmul.c -o libbcmath/src/recmul.lo  -MMD -MF libbcmath/src/recmul.dep -MT libbcmath/src/recmul.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/sqrt.c -o libbcmath/src/sqrt.lo  -MMD -MF libbcmath/src/sqrt.dep -MT libbcmath/src/sqrt.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/zero.c -o libbcmath/src/zero.lo  -MMD -MF libbcmath/src/zero.dep -MT libbcmath/src/zero.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/debug.c -o libbcmath/src/debug.lo  -MMD -MF libbcmath/src/debug.dep -MT libbcmath/src/debug.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/doaddsub.c -o libbcmath/src/doaddsub.lo  -MMD -MF libbcmath/src/doaddsub.dep -MT libbcmath/src/doaddsub.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/nearzero.c -o libbcmath/src/nearzero.lo  -MMD -MF libbcmath/src/nearzero.dep -MT libbcmath/src/nearzero.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/num2str.c -o libbcmath/src/num2str.lo  -MMD -MF libbcmath/src/num2str.dep -MT libbcmath/src/num2str.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/raise.c -o libbcmath/src/raise.lo  -MMD -MF libbcmath/src/raise.dep -MT libbcmath/src/raise.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/rmzero.c -o libbcmath/src/rmzero.lo  -MMD -MF libbcmath/src/rmzero.dep -MT libbcmath/src/rmzero.lo
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/str2num.c -o libbcmath/src/str2num.lo  -MMD -MF libbcmath/src/str2num.dep -MT libbcmath/src/str2num.lo
mkdir .libs
mkdir libbcmath/src/.libs
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/bcmath.c -MMD -MF bcmath.dep -MT bcmath.lo  -fPIC -DPIC -o .libs/bcmath.o
mkdir libbcmath/src/.libs
mkdir: can't create directory 'libbcmath/src/.libs': File exists
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/raisemod.c -MMD -MF libbcmath/src/raisemod.dep -MT libbcmath/src/raisemod.lo  -fPIC -DPIC -o libbcmath/src/.libs/raisemod.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/div.c -MMD -MF libbcmath/src/div.dep -MT libbcmath/src/div.lo  -fPIC -DPIC -o libbcmath/src/.libs/div.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/output.c -MMD -MF libbcmath/src/output.dep -MT libbcmath/src/output.lo  -fPIC -DPIC -o libbcmath/src/.libs/output.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/neg.c -MMD -MF libbcmath/src/neg.dep -MT libbcmath/src/neg.lo  -fPIC -DPIC -o libbcmath/src/.libs/neg.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/add.c -MMD -MF libbcmath/src/add.dep -MT libbcmath/src/add.lo  -fPIC -DPIC -o libbcmath/src/.libs/add.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/init.c -MMD -MF libbcmath/src/init.dep -MT libbcmath/src/init.lo  -fPIC -DPIC -o libbcmath/src/.libs/init.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/divmod.c -MMD -MF libbcmath/src/divmod.dep -MT libbcmath/src/divmod.lo  -fPIC -DPIC -o libbcmath/src/.libs/divmod.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/sub.c -MMD -MF libbcmath/src/sub.dep -MT libbcmath/src/sub.lo  -fPIC -DPIC -o libbcmath/src/.libs/sub.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/num2long.c -MMD -MF libbcmath/src/num2long.dep -MT libbcmath/src/num2long.lo  -fPIC -DPIC -o libbcmath/src/.libs/num2long.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/nearzero.c -MMD -MF libbcmath/src/nearzero.dep -MT libbcmath/src/nearzero.lo  -fPIC -DPIC -o libbcmath/src/.libs/nearzero.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/int2num.c -MMD -MF libbcmath/src/int2num.dep -MT libbcmath/src/int2num.lo  -fPIC -DPIC -o libbcmath/src/.libs/int2num.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/sqrt.c -MMD -MF libbcmath/src/sqrt.dep -MT libbcmath/src/sqrt.lo  -fPIC -DPIC -o libbcmath/src/.libs/sqrt.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/compare.c -MMD -MF libbcmath/src/compare.dep -MT libbcmath/src/compare.lo  -fPIC -DPIC -o libbcmath/src/.libs/compare.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/zero.c -MMD -MF libbcmath/src/zero.dep -MT libbcmath/src/zero.lo  -fPIC -DPIC -o libbcmath/src/.libs/zero.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/debug.c -MMD -MF libbcmath/src/debug.dep -MT libbcmath/src/debug.lo  -fPIC -DPIC -o libbcmath/src/.libs/debug.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/raise.c -MMD -MF libbcmath/src/raise.dep -MT libbcmath/src/raise.lo  -fPIC -DPIC -o libbcmath/src/.libs/raise.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/recmul.c -MMD -MF libbcmath/src/recmul.dep -MT libbcmath/src/recmul.lo  -fPIC -DPIC -o libbcmath/src/.libs/recmul.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/rmzero.c -MMD -MF libbcmath/src/rmzero.dep -MT libbcmath/src/rmzero.lo  -fPIC -DPIC -o libbcmath/src/.libs/rmzero.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/doaddsub.c -MMD -MF libbcmath/src/doaddsub.dep -MT libbcmath/src/doaddsub.lo  -fPIC -DPIC -o libbcmath/src/.libs/doaddsub.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/num2str.c -MMD -MF libbcmath/src/num2str.dep -MT libbcmath/src/num2str.lo  -fPIC -DPIC -o libbcmath/src/.libs/num2str.o
 cc -I. -I/usr/src/php/ext/bcmath -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/bcmath/libbcmath/src -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/bcmath/libbcmath/src/str2num.c -MMD -MF libbcmath/src/str2num.dep -MT libbcmath/src/str2num.lo  -fPIC -DPIC -o libbcmath/src/.libs/str2num.o
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=link cc -shared -I/usr/src/php/ext/bcmath/include -I/usr/src/php/ext/bcmath/main -I/usr/src/php/ext/bcmath -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE  -Wl,-O1 -pie  -o bcmath.la -export-dynamic -avoid-version -prefer-pic -module -rpath /usr/src/php/ext/bcmath/modules  bcmath.lo libbcmath/src/add.lo libbcmath/src/div.lo libbcmath/src/init.lo libbcmath/src/neg.lo libbcmath/src/raisemod.lo libbcmath/src/sub.lo libbcmath/src/compare.lo libbcmath/src/divmod.lo libbcmath/src/int2num.lo libbcmath/src/num2long.lo libbcmath/src/output.lo libbcmath/src/recmul.lo libbcmath/src/sqrt.lo libbcmath/src/zero.lo libbcmath/src/debug.lo libbcmath/src/doaddsub.lo libbcmath/src/nearzero.lo libbcmath/src/num2str.lo libbcmath/src/raise.lo libbcmath/src/rmzero.lo libbcmath/src/str2num.lo 
cc -shared  .libs/bcmath.o libbcmath/src/.libs/add.o libbcmath/src/.libs/div.o libbcmath/src/.libs/init.o libbcmath/src/.libs/neg.o libbcmath/src/.libs/raisemod.o libbcmath/src/.libs/sub.o libbcmath/src/.libs/compare.o libbcmath/src/.libs/divmod.o libbcmath/src/.libs/int2num.o libbcmath/src/.libs/num2long.o libbcmath/src/.libs/output.o libbcmath/src/.libs/recmul.o libbcmath/src/.libs/sqrt.o libbcmath/src/.libs/zero.o libbcmath/src/.libs/debug.o libbcmath/src/.libs/doaddsub.o libbcmath/src/.libs/nearzero.o libbcmath/src/.libs/num2str.o libbcmath/src/.libs/raise.o libbcmath/src/.libs/rmzero.o libbcmath/src/.libs/str2num.o   -Wl,-O1 -Wl,-soname -Wl,bcmath.so -o .libs/bcmath.so
creating bcmath.la
(cd .libs && rm -f bcmath.la && ln -s ../bcmath.la bcmath.la)
/bin/sh /usr/src/php/ext/bcmath/libtool --tag=CC --mode=install cp ./bcmath.la /usr/src/php/ext/bcmath/modules
cp ./.libs/bcmath.so /usr/src/php/ext/bcmath/modules/bcmath.so
cp ./.libs/bcmath.lai /usr/src/php/ext/bcmath/modules/bcmath.la
PATH="$PATH:/sbin" ldconfig -n /usr/src/php/ext/bcmath/modules
----------------------------------------------------------------------
Libraries have been installed in:
   /usr/src/php/ext/bcmath/modules
If you ever happen to want to link against installed libraries
in a given directory, LIBDIR, you must either use libtool, and
specify the full pathname of the library, or use the `-LLIBDIR'
flag during linking and do at least one of the following:
   - add LIBDIR to the `LD_LIBRARY_PATH' environment variable
     during execution
   - add LIBDIR to the `LD_RUN_PATH' environment variable
     during linking
   - use the `-Wl,--rpath -Wl,LIBDIR' linker flag
See any operating system documentation about shared libraries for
more information, such as the ld(1) and ld.so(8) manual pages.
----------------------------------------------------------------------
Build complete.
Don't forget to run 'make test'.
+ strip --strip-all modules/bcmath.so
Installing shared extensions:     /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
find . -name \*.gcno -o -name \*.gcda | xargs rm -f
find . -name \*.lo -o -name \*.o -o -name \*.dep | xargs rm -f
find . -name \*.la -o -name \*.a | xargs rm -f
find . -name \*.so | xargs rm -f
find . -name .libs -a -type d|xargs rm -rf
rm -f libphp.la      modules/* libs/*
rm -f ext/opcache/jit/zend_jit_x86.c
rm -f ext/opcache/jit/zend_jit_arm64.c
rm -f ext/opcache/minilua
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/gd.c -o gd.lo  -MMD -MF gd.dep -MT gd.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd.c -o libgd/gd.lo  -MMD -MF libgd/gd.dep -MT libgd/gd.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_gd.c -o libgd/gd_gd.lo  -MMD -MF libgd/gd_gd.dep -MT libgd/gd_gd.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_gd2.c -o libgd/gd_gd2.lo  -MMD -MF libgd/gd_gd2.dep -MT libgd/gd_gd2.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_io.c -o libgd/gd_io.lo  -MMD -MF libgd/gd_io.dep -MT libgd/gd_io.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_io_dp.c -o libgd/gd_io_dp.lo  -MMD -MF libgd/gd_io_dp.dep -MT libgd/gd_io_dp.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_io_file.c -o libgd/gd_io_file.lo  -MMD -MF libgd/gd_io_file.dep -MT libgd/gd_io_file.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_ss.c -o libgd/gd_ss.lo  -MMD -MF libgd/gd_ss.dep -MT libgd/gd_ss.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_io_ss.c -o libgd/gd_io_ss.lo  -MMD -MF libgd/gd_io_ss.dep -MT libgd/gd_io_ss.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_webp.c -o libgd/gd_webp.lo  -MMD -MF libgd/gd_webp.dep -MT libgd/gd_webp.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_avif.c -o libgd/gd_avif.lo  -MMD -MF libgd/gd_avif.dep -MT libgd/gd_avif.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_png.c -o libgd/gd_png.lo  -MMD -MF libgd/gd_png.dep -MT libgd/gd_png.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_jpeg.c -o libgd/gd_jpeg.lo  -MMD -MF libgd/gd_jpeg.dep -MT libgd/gd_jpeg.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdxpm.c -o libgd/gdxpm.lo  -MMD -MF libgd/gdxpm.dep -MT libgd/gdxpm.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfontt.c -o libgd/gdfontt.lo  -MMD -MF libgd/gdfontt.dep -MT libgd/gdfontt.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfonts.c -o libgd/gdfonts.lo  -MMD -MF libgd/gdfonts.dep -MT libgd/gdfonts.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfontmb.c -o libgd/gdfontmb.lo  -MMD -MF libgd/gdfontmb.dep -MT libgd/gdfontmb.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfontl.c -o libgd/gdfontl.lo  -MMD -MF libgd/gdfontl.dep -MT libgd/gdfontl.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfontg.c -o libgd/gdfontg.lo  -MMD -MF libgd/gdfontg.dep -MT libgd/gdfontg.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdtables.c -o libgd/gdtables.lo  -MMD -MF libgd/gdtables.dep -MT libgd/gdtables.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdft.c -o libgd/gdft.lo  -MMD -MF libgd/gdft.dep -MT libgd/gdft.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdcache.c -o libgd/gdcache.lo  -MMD -MF libgd/gdcache.dep -MT libgd/gdcache.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdkanji.c -o libgd/gdkanji.lo  -MMD -MF libgd/gdkanji.dep -MT libgd/gdkanji.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/wbmp.c -o libgd/wbmp.lo  -MMD -MF libgd/wbmp.dep -MT libgd/wbmp.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_wbmp.c -o libgd/gd_wbmp.lo  -MMD -MF libgd/gd_wbmp.dep -MT libgd/gd_wbmp.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdhelpers.c -o libgd/gdhelpers.lo  -MMD -MF libgd/gdhelpers.dep -MT libgd/gdhelpers.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_topal.c -o libgd/gd_topal.lo  -MMD -MF libgd/gd_topal.dep -MT libgd/gd_topal.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_gif_in.c -o libgd/gd_gif_in.lo  -MMD -MF libgd/gd_gif_in.dep -MT libgd/gd_gif_in.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_xbm.c -o libgd/gd_xbm.lo  -MMD -MF libgd/gd_xbm.dep -MT libgd/gd_xbm.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_gif_out.c -o libgd/gd_gif_out.lo  -MMD -MF libgd/gd_gif_out.dep -MT libgd/gd_gif_out.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_security.c -o libgd/gd_security.lo  -MMD -MF libgd/gd_security.dep -MT libgd/gd_security.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_filter.c -o libgd/gd_filter.lo  -MMD -MF libgd/gd_filter.dep -MT libgd/gd_filter.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_pixelate.c -o libgd/gd_pixelate.lo  -MMD -MF libgd/gd_pixelate.dep -MT libgd/gd_pixelate.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_rotate.c -o libgd/gd_rotate.lo  -MMD -MF libgd/gd_rotate.dep -MT libgd/gd_rotate.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_color_match.c -o libgd/gd_color_match.lo  -MMD -MF libgd/gd_color_match.dep -MT libgd/gd_color_match.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_transform.c -o libgd/gd_transform.lo  -MMD -MF libgd/gd_transform.dep -MT libgd/gd_transform.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_crop.c -o libgd/gd_crop.lo  -MMD -MF libgd/gd_crop.dep -MT libgd/gd_crop.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_interpolation.c -o libgd/gd_interpolation.lo  -MMD -MF libgd/gd_interpolation.dep -MT libgd/gd_interpolation.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_matrix.c -o libgd/gd_matrix.lo  -MMD -MF libgd/gd_matrix.dep -MT libgd/gd_matrix.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_bmp.c -o libgd/gd_bmp.lo  -MMD -MF libgd/gd_bmp.dep -MT libgd/gd_bmp.lo
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_tga.c -o libgd/gd_tga.lo  -MMD -MF libgd/gd_tga.dep -MT libgd/gd_tga.lo
mkdir libgd/.libs
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_gd2.c -MMD -MF libgd/gd_gd2.dep -MT libgd/gd_gd2.lo  -fPIC -DPIC -o libgd/.libs/gd_gd2.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_avif.c -MMD -MF libgd/gd_avif.dep -MT libgd/gd_avif.lo  -fPIC -DPIC -o libgd/.libs/gd_avif.o
mkdir .libs
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/gd.c -MMD -MF gd.dep -MT gd.lo  -fPIC -DPIC -o .libs/gd.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_gd.c -MMD -MF libgd/gd_gd.dep -MT libgd/gd_gd.lo  -fPIC -DPIC -o libgd/.libs/gd_gd.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_png.c -MMD -MF libgd/gd_png.dep -MT libgd/gd_png.lo  -fPIC -DPIC -o libgd/.libs/gd_png.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_io_file.c -MMD -MF libgd/gd_io_file.dep -MT libgd/gd_io_file.lo  -fPIC -DPIC -o libgd/.libs/gd_io_file.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_ss.c -MMD -MF libgd/gd_ss.dep -MT libgd/gd_ss.lo  -fPIC -DPIC -o libgd/.libs/gd_ss.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd.c -MMD -MF libgd/gd.dep -MT libgd/gd.lo  -fPIC -DPIC -o libgd/.libs/gd.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_io.c -MMD -MF libgd/gd_io.dep -MT libgd/gd_io.lo  -fPIC -DPIC -o libgd/.libs/gd_io.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_jpeg.c -MMD -MF libgd/gd_jpeg.dep -MT libgd/gd_jpeg.lo  -fPIC -DPIC -o libgd/.libs/gd_jpeg.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_wbmp.c -MMD -MF libgd/gd_wbmp.dep -MT libgd/gd_wbmp.lo  -fPIC -DPIC -o libgd/.libs/gd_wbmp.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfontt.c -MMD -MF libgd/gdfontt.dep -MT libgd/gdfontt.lo  -fPIC -DPIC -o libgd/.libs/gdfontt.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_io_ss.c -MMD -MF libgd/gd_io_ss.dep -MT libgd/gd_io_ss.lo  -fPIC -DPIC -o libgd/.libs/gd_io_ss.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdxpm.c -MMD -MF libgd/gdxpm.dep -MT libgd/gdxpm.lo  -fPIC -DPIC -o libgd/.libs/gdxpm.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfontg.c -MMD -MF libgd/gdfontg.dep -MT libgd/gdfontg.lo  -fPIC -DPIC -o libgd/.libs/gdfontg.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_webp.c -MMD -MF libgd/gd_webp.dep -MT libgd/gd_webp.lo  -fPIC -DPIC -o libgd/.libs/gd_webp.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_xbm.c -MMD -MF libgd/gd_xbm.dep -MT libgd/gd_xbm.lo  -fPIC -DPIC -o libgd/.libs/gd_xbm.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdcache.c -MMD -MF libgd/gdcache.dep -MT libgd/gdcache.lo  -fPIC -DPIC -o libgd/.libs/gdcache.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfontl.c -MMD -MF libgd/gdfontl.dep -MT libgd/gdfontl.lo  -fPIC -DPIC -o libgd/.libs/gdfontl.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfontmb.c -MMD -MF libgd/gdfontmb.dep -MT libgd/gdfontmb.lo  -fPIC -DPIC -o libgd/.libs/gdfontmb.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_topal.c -MMD -MF libgd/gd_topal.dep -MT libgd/gd_topal.lo  -fPIC -DPIC -o libgd/.libs/gd_topal.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_security.c -MMD -MF libgd/gd_security.dep -MT libgd/gd_security.lo  -fPIC -DPIC -o libgd/.libs/gd_security.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdkanji.c -MMD -MF libgd/gdkanji.dep -MT libgd/gdkanji.lo  -fPIC -DPIC -o libgd/.libs/gdkanji.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdft.c -MMD -MF libgd/gdft.dep -MT libgd/gdft.lo  -fPIC -DPIC -o libgd/.libs/gdft.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_io_dp.c -MMD -MF libgd/gd_io_dp.dep -MT libgd/gd_io_dp.lo  -fPIC -DPIC -o libgd/.libs/gd_io_dp.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_bmp.c -MMD -MF libgd/gd_bmp.dep -MT libgd/gd_bmp.lo  -fPIC -DPIC -o libgd/.libs/gd_bmp.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdtables.c -MMD -MF libgd/gdtables.dep -MT libgd/gdtables.lo  -fPIC -DPIC -o libgd/.libs/gdtables.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdfonts.c -MMD -MF libgd/gdfonts.dep -MT libgd/gdfonts.lo  -fPIC -DPIC -o libgd/.libs/gdfonts.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_rotate.c -MMD -MF libgd/gd_rotate.dep -MT libgd/gd_rotate.lo  -fPIC -DPIC -o libgd/.libs/gd_rotate.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_gif_in.c -MMD -MF libgd/gd_gif_in.dep -MT libgd/gd_gif_in.lo  -fPIC -DPIC -o libgd/.libs/gd_gif_in.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gdhelpers.c -MMD -MF libgd/gdhelpers.dep -MT libgd/gdhelpers.lo  -fPIC -DPIC -o libgd/.libs/gdhelpers.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_color_match.c -MMD -MF libgd/gd_color_match.dep -MT libgd/gd_color_match.lo  -fPIC -DPIC -o libgd/.libs/gd_color_match.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_gif_out.c -MMD -MF libgd/gd_gif_out.dep -MT libgd/gd_gif_out.lo  -fPIC -DPIC -o libgd/.libs/gd_gif_out.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_pixelate.c -MMD -MF libgd/gd_pixelate.dep -MT libgd/gd_pixelate.lo  -fPIC -DPIC -o libgd/.libs/gd_pixelate.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_matrix.c -MMD -MF libgd/gd_matrix.dep -MT libgd/gd_matrix.lo  -fPIC -DPIC -o libgd/.libs/gd_matrix.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/wbmp.c -MMD -MF libgd/wbmp.dep -MT libgd/wbmp.lo  -fPIC -DPIC -o libgd/.libs/wbmp.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_tga.c -MMD -MF libgd/gd_tga.dep -MT libgd/gd_tga.lo  -fPIC -DPIC -o libgd/.libs/gd_tga.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_crop.c -MMD -MF libgd/gd_crop.dep -MT libgd/gd_crop.lo  -fPIC -DPIC -o libgd/.libs/gd_crop.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_filter.c -MMD -MF libgd/gd_filter.dep -MT libgd/gd_filter.lo  -fPIC -DPIC -o libgd/.libs/gd_filter.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_transform.c -MMD -MF libgd/gd_transform.dep -MT libgd/gd_transform.lo  -fPIC -DPIC -o libgd/.libs/gd_transform.o
 cc -I. -I/usr/src/php/ext/gd -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -I/usr/src/php/ext/gd/libgd -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/gd/libgd/gd_interpolation.c -MMD -MF libgd/gd_interpolation.dep -MT libgd/gd_interpolation.lo  -fPIC -DPIC -o libgd/.libs/gd_interpolation.o
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=link cc -shared -I/usr/src/php/ext/gd/include -I/usr/src/php/ext/gd/main -I/usr/src/php/ext/gd -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libpng16 -I/usr/include/freetype2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE  -Wl,-O1 -pie  -o gd.la -export-dynamic -avoid-version -prefer-pic -module -rpath /usr/src/php/ext/gd/modules  gd.lo libgd/gd.lo libgd/gd_gd.lo libgd/gd_gd2.lo libgd/gd_io.lo libgd/gd_io_dp.lo libgd/gd_io_file.lo libgd/gd_ss.lo libgd/gd_io_ss.lo libgd/gd_webp.lo libgd/gd_avif.lo libgd/gd_png.lo libgd/gd_jpeg.lo libgd/gdxpm.lo libgd/gdfontt.lo libgd/gdfonts.lo libgd/gdfontmb.lo libgd/gdfontl.lo libgd/gdfontg.lo libgd/gdtables.lo libgd/gdft.lo libgd/gdcache.lo libgd/gdkanji.lo libgd/wbmp.lo libgd/gd_wbmp.lo libgd/gdhelpers.lo libgd/gd_topal.lo libgd/gd_gif_in.lo libgd/gd_xbm.lo libgd/gd_gif_out.lo libgd/gd_security.lo libgd/gd_filter.lo libgd/gd_pixelate.lo libgd/gd_rotate.lo libgd/gd_color_match.lo libgd/gd_transform.lo libgd/gd_crop.lo libgd/gd_interpolation.lo libgd/gd_matrix.lo libgd/gd_bmp.lo libgd/gd_tga.lo -lz -lpng16 -ljpeg -lfreetype
cc -shared  .libs/gd.o libgd/.libs/gd.o libgd/.libs/gd_gd.o libgd/.libs/gd_gd2.o libgd/.libs/gd_io.o libgd/.libs/gd_io_dp.o libgd/.libs/gd_io_file.o libgd/.libs/gd_ss.o libgd/.libs/gd_io_ss.o libgd/.libs/gd_webp.o libgd/.libs/gd_avif.o libgd/.libs/gd_png.o libgd/.libs/gd_jpeg.o libgd/.libs/gdxpm.o libgd/.libs/gdfontt.o libgd/.libs/gdfonts.o libgd/.libs/gdfontmb.o libgd/.libs/gdfontl.o libgd/.libs/gdfontg.o libgd/.libs/gdtables.o libgd/.libs/gdft.o libgd/.libs/gdcache.o libgd/.libs/gdkanji.o libgd/.libs/wbmp.o libgd/.libs/gd_wbmp.o libgd/.libs/gdhelpers.o libgd/.libs/gd_topal.o libgd/.libs/gd_gif_in.o libgd/.libs/gd_xbm.o libgd/.libs/gd_gif_out.o libgd/.libs/gd_security.o libgd/.libs/gd_filter.o libgd/.libs/gd_pixelate.o libgd/.libs/gd_rotate.o libgd/.libs/gd_color_match.o libgd/.libs/gd_transform.o libgd/.libs/gd_crop.o libgd/.libs/gd_interpolation.o libgd/.libs/gd_matrix.o libgd/.libs/gd_bmp.o libgd/.libs/gd_tga.o  -lz -lpng16 -ljpeg -lfreetype  -Wl,-O1 -Wl,-soname -Wl,gd.so -o .libs/gd.so
creating gd.la
(cd .libs && rm -f gd.la && ln -s ../gd.la gd.la)
/bin/sh /usr/src/php/ext/gd/libtool --tag=CC --mode=install cp ./gd.la /usr/src/php/ext/gd/modules
cp ./.libs/gd.so /usr/src/php/ext/gd/modules/gd.so
cp ./.libs/gd.lai /usr/src/php/ext/gd/modules/gd.la
PATH="$PATH:/sbin" ldconfig -n /usr/src/php/ext/gd/modules
----------------------------------------------------------------------
Libraries have been installed in:
   /usr/src/php/ext/gd/modules
If you ever happen to want to link against installed libraries
in a given directory, LIBDIR, you must either use libtool, and
specify the full pathname of the library, or use the `-LLIBDIR'
flag during linking and do at least one of the following:
   - add LIBDIR to the `LD_LIBRARY_PATH' environment variable
     during execution
   - add LIBDIR to the `LD_RUN_PATH' environment variable
     during linking
   - use the `-Wl,--rpath -Wl,LIBDIR' linker flag
See any operating system documentation about shared libraries for
more information, such as the ld(1) and ld.so(8) manual pages.
----------------------------------------------------------------------
Build complete.
Don't forget to run 'make test'.
+ strip --strip-all modules/gd.so
Installing shared extensions:     /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
Installing header files:          /usr/local/include/php/
find . -name \*.gcno -o -name \*.gcda | xargs rm -f
find . -name \*.lo -o -name \*.o -o -name \*.dep | xargs rm -f
find . -name \*.la -o -name \*.a | xargs rm -f
find . -name \*.so | xargs rm -f
find . -name .libs -a -type d|xargs rm -rf
rm -f libphp.la      modules/* libs/*
rm -f ext/opcache/jit/zend_jit_x86.c
rm -f ext/opcache/jit/zend_jit_arm64.c
rm -f ext/opcache/minilua
Configuring for:
PHP Api Version:         20220829
Zend Module Api No:      20220829
Zend Extension Api No:   420220829
checking for grep that handles long lines and -e... 
/bin/grep
checking for egrep... 
/bin/grep -E
checking for a sed that does not truncate output... 
/bin/sed
checking for pkg-config... 
/usr/bin/pkg-config
checking pkg-config is at least version 0.9.0... 
yes
checking for cc... 
cc
checking whether the C compiler works... 
yes
checking for C compiler default output file name... a.out
checking for suffix of executables... 
checking whether we are cross compiling... 
no
checking for suffix of object files... 
o
checking whether the compiler supports GNU C... 
yes
checking whether cc accepts -g... 
yes
checking for cc option to enable C11 features... 
none needed
checking how to run the C preprocessor... 
cc -E
checking for egrep -e... 
(cached) 
/bin/grep -E
checking for icc... 
no
checking for suncc... 
no
checking for system library directory... 
lib
checking if compiler supports -Wl,-rpath,... 
yes
checking build system type... 
x86_64-pc-linux-musl
checking host system type... 
x86_64-pc-linux-musl
checking target system type... 
x86_64-pc-linux-musl
checking for PHP prefix... /usr/local
checking for PHP includes... 
-I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib
checking for PHP extension directory... 
/usr/local/lib/php/extensions/no-debug-non-zts-20220829
checking for PHP installed headers prefix... /usr/local/include/php
checking if debug is enabled... 
no
checking if zts is enabled... 
no
checking for gawk... 
no
checking for nawk... 
no
checking for awk... 
awk
checking if awk is broken... 
no
checking whether to enable XML support... 
yes, shared
checking whether to build with expat support... 
no
checking for libxml-2.0 >= 2.9.0... 
yes
checking for a sed that does not truncate output... 
/bin/sed
checking for ld used by cc... 
/usr/x86_64-alpine-linux-musl/bin/ld
checking if the linker (/usr/x86_64-alpine-linux-musl/bin/ld) is GNU ld... 
yes
checking for /usr/x86_64-alpine-linux-musl/bin/ld option to reload object files... 
-r
checking for BSD-compatible nm... 
/usr/bin/nm -B
checking whether ln -s works... 
yes
checking how to recognize dependent libraries... 
pass_all
checking for stdio.h... 
yes
checking for stdlib.h... 
yes
checking for string.h... 
yes
checking for inttypes.h... 
yes
checking for stdint.h... 
yes
checking for strings.h... 
yes
checking for sys/stat.h... 
yes
checking for sys/types.h... 
yes
checking for unistd.h... 
yes
checking for dlfcn.h... 
yes
checking the maximum length of command line arguments... 
98304
checking command to parse /usr/bin/nm -B output from cc object... 
ok
checking for objdir... 
.libs
checking for ar... 
ar
checking for ranlib... 
ranlib
checking for strip... 
strip
checking if cc supports -fno-rtti -fno-exceptions... 
no
checking for cc option to produce PIC... 
-fPIC
checking if cc PIC flag -fPIC works... 
yes
checking if cc static flag -static works... 
yes
checking if cc supports -c -o file.o... 
yes
checking whether the cc linker (/usr/x86_64-alpine-linux-musl/bin/ld -m elf_x86_64) supports shared libraries... 
yes
checking whether -lc should be explicitly linked in... 
no
checking dynamic linker characteristics... 
GNU/Linux ld.so
checking how to hardcode library paths into programs... 
immediate
checking whether stripping libraries is possible... 
yes
checking if libtool supports shared libraries... yes
checking whether to build shared libraries... 
yes
checking whether to build static libraries... 
no
creating libtool
appending configuration tag "CXX" to libtool
configure: patching config.h.in
configure: creating ./config.status
config.status: creating config.h
/bin/sh /usr/src/php/ext/xml/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/xml -I/usr/src/php/ext/xml/include -I/usr/src/php/ext/xml/main -I/usr/src/php/ext/xml -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/xml/xml.c -o xml.lo  -MMD -MF xml.dep -MT xml.lo
/bin/sh /usr/src/php/ext/xml/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/xml -I/usr/src/php/ext/xml/include -I/usr/src/php/ext/xml/main -I/usr/src/php/ext/xml -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/xml/compat.c -o compat.lo  -MMD -MF compat.dep -MT compat.lo
mkdir .libs
 cc -I. -I/usr/src/php/ext/xml -I/usr/src/php/ext/xml/include -I/usr/src/php/ext/xml/main -I/usr/src/php/ext/xml -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/xml/compat.c -MMD -MF compat.dep -MT compat.lo  -fPIC -DPIC -o .libs/compat.o
 cc -I. -I/usr/src/php/ext/xml -I/usr/src/php/ext/xml/include -I/usr/src/php/ext/xml/main -I/usr/src/php/ext/xml -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/xml/xml.c -MMD -MF xml.dep -MT xml.lo  -fPIC -DPIC -o .libs/xml.o
/bin/sh /usr/src/php/ext/xml/libtool --tag=CC --mode=link cc -shared -I/usr/src/php/ext/xml/include -I/usr/src/php/ext/xml/main -I/usr/src/php/ext/xml -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE  -Wl,-O1 -pie  -o xml.la -export-dynamic -avoid-version -prefer-pic -module -rpath /usr/src/php/ext/xml/modules  xml.lo compat.lo -lxml2
cc -shared  .libs/xml.o .libs/compat.o  -lxml2  -Wl,-O1 -Wl,-soname -Wl,xml.so -o .libs/xml.so
creating xml.la
(cd .libs && rm -f xml.la && ln -s ../xml.la xml.la)
/bin/sh /usr/src/php/ext/xml/libtool --tag=CC --mode=install cp ./xml.la /usr/src/php/ext/xml/modules
cp ./.libs/xml.so /usr/src/php/ext/xml/modules/xml.so
cp ./.libs/xml.lai /usr/src/php/ext/xml/modules/xml.la
PATH="$PATH:/sbin" ldconfig -n /usr/src/php/ext/xml/modules
----------------------------------------------------------------------
Libraries have been installed in:
   /usr/src/php/ext/xml/modules
If you ever happen to want to link against installed libraries
in a given directory, LIBDIR, you must either use libtool, and
specify the full pathname of the library, or use the `-LLIBDIR'
flag during linking and do at least one of the following:
   - add LIBDIR to the `LD_LIBRARY_PATH' environment variable
     during execution
   - add LIBDIR to the `LD_RUN_PATH' environment variable
     during linking
   - use the `-Wl,--rpath -Wl,LIBDIR' linker flag
See any operating system documentation about shared libraries for
more information, such as the ld(1) and ld.so(8) manual pages.
----------------------------------------------------------------------
Build complete.
Don't forget to run 'make test'.
+ strip --strip-all modules/xml.so
Installing shared extensions:     /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
Installing header files:          /usr/local/include/php/
warning: xml (xml) is already loaded!
find . -name \*.gcno -o -name \*.gcda | xargs rm -f
find . -name \*.lo -o -name \*.o -o -name \*.dep | xargs rm -f
find . -name \*.la -o -name \*.a | xargs rm -f
find . -name \*.so | xargs rm -f
find . -name .libs -a -type d|xargs rm -rf
rm -f libphp.la      modules/* libs/*
rm -f ext/opcache/jit/zend_jit_x86.c
rm -f ext/opcache/jit/zend_jit_arm64.c
rm -f ext/opcache/minilua
Configuring for:
PHP Api Version:         20220829
Zend Module Api No:      20220829
Zend Extension Api No:   420220829
checking for grep that handles long lines and -e... 
/bin/grep
checking for egrep... 
/bin/grep -E
checking for a sed that does not truncate output... 
/bin/sed
checking for pkg-config... 
/usr/bin/pkg-config
checking pkg-config is at least version 0.9.0... 
yes
checking for cc... 
cc
checking whether the C compiler works... 
yes
checking for C compiler default output file name... a.out
checking for suffix of executables... 
checking whether we are cross compiling... 
no
checking for suffix of object files... 
o
checking whether the compiler supports GNU C... 
yes
checking whether cc accepts -g... 
yes
checking for cc option to enable C11 features... 
none needed
checking how to run the C preprocessor... 
cc -E
checking for egrep -e... 
(cached) 
/bin/grep -E
checking for icc... 
no
checking for suncc... 
no
checking for system library directory... lib
checking if compiler supports -Wl,-rpath,... 
yes
checking build system type... 
x86_64-pc-linux-musl
checking host system type... 
x86_64-pc-linux-musl
checking target system type... 
x86_64-pc-linux-musl
checking for PHP prefix... /usr/local
checking for PHP includes... 
-I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib
checking for PHP extension directory... 
/usr/local/lib/php/extensions/no-debug-non-zts-20220829
checking for PHP installed headers prefix... 
/usr/local/include/php
checking if debug is enabled... 
no
checking if zts is enabled... 
no
checking for gawk... 
no
checking for nawk... 
no
checking for awk... 
awk
checking if awk is broken... 
no
checking for zip archive read/write support... 
yes, shared
checking for libzip >= 0.11 libzip != 1.3.1 libzip != 1.7.0... 
yes
checking for zip_file_set_mtime in -lzip... 
yes
checking for zip_file_set_encryption in -lzip... 
yes
checking for zip_libzip_version in -lzip... 
yes
checking for zip_register_progress_callback_with_state in -lzip... 
yes
checking for zip_register_cancel_callback_with_state in -lzip... 
yes
checking for zip_compression_method_supported in -lzip... 
yes
checking for a sed that does not truncate output... 
/bin/sed
checking for ld used by cc... 
/usr/x86_64-alpine-linux-musl/bin/ld
checking if the linker (/usr/x86_64-alpine-linux-musl/bin/ld) is GNU ld... 
yes
checking for /usr/x86_64-alpine-linux-musl/bin/ld option to reload object files... 
-r
checking for BSD-compatible nm... 
/usr/bin/nm -B
checking whether ln -s works... 
yes
checking how to recognize dependent libraries... 
pass_all
checking for stdio.h... 
yes
checking for stdlib.h... 
yes
checking for string.h... 
yes
checking for inttypes.h... 
yes
checking for stdint.h... 
yes
checking for strings.h... 
yes
checking for sys/stat.h... 
yes
checking for sys/types.h... 
yes
checking for unistd.h... 
yes
checking for dlfcn.h... 
yes
checking the maximum length of command line arguments... 
98304
checking command to parse /usr/bin/nm -B output from cc object... 
ok
checking for objdir... 
.libs
checking for ar... 
ar
checking for ranlib... ranlib
checking for strip... 
strip
checking if cc supports -fno-rtti -fno-exceptions... 
no
checking for cc option to produce PIC... 
-fPIC
checking if cc PIC flag -fPIC works... 
yes
checking if cc static flag -static works... 
yes
checking if cc supports -c -o file.o... 
yes
checking whether the cc linker (/usr/x86_64-alpine-linux-musl/bin/ld -m elf_x86_64) supports shared libraries... 
yes
checking whether -lc should be explicitly linked in... 
no
checking dynamic linker characteristics... 
GNU/Linux ld.so
checking how to hardcode library paths into programs... 
immediate
checking whether stripping libraries is possible... 
yes
checking if libtool supports shared libraries... 
yes
checking whether to build shared libraries... 
yes
checking whether to build static libraries... 
no
creating libtool
appending configuration tag "CXX" to libtool
configure: patching config.h.in
configure: creating ./config.status
config.status: creating config.h
/bin/sh /usr/src/php/ext/zip/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/zip -I/usr/src/php/ext/zip/include -I/usr/src/php/ext/zip/main -I/usr/src/php/ext/zip -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE    -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/zip/php_zip.c -o php_zip.lo  -MMD -MF php_zip.dep -MT php_zip.lo
/bin/sh /usr/src/php/ext/zip/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/zip -I/usr/src/php/ext/zip/include -I/usr/src/php/ext/zip/main -I/usr/src/php/ext/zip -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE    -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/zip/zip_stream.c -o zip_stream.lo  -MMD -MF zip_stream.dep -MT zip_stream.lo
mkdir .libs
 cc -I. -I/usr/src/php/ext/zip -I/usr/src/php/ext/zip/include -I/usr/src/php/ext/zip/main -I/usr/src/php/ext/zip -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/zip/zip_stream.c -MMD -MF zip_stream.dep -MT zip_stream.lo  -fPIC -DPIC -o .libs/zip_stream.o
 cc -I. -I/usr/src/php/ext/zip -I/usr/src/php/ext/zip/include -I/usr/src/php/ext/zip/main -I/usr/src/php/ext/zip -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/zip/php_zip.c -MMD -MF php_zip.dep -MT php_zip.lo  -fPIC -DPIC -o .libs/php_zip.o
/bin/sh /usr/src/php/ext/zip/libtool --tag=CC --mode=link cc -shared -I/usr/src/php/ext/zip/include -I/usr/src/php/ext/zip/main -I/usr/src/php/ext/zip -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE  -Wl,-O1 -pie  -o zip.la -export-dynamic -avoid-version -prefer-pic -module -rpath /usr/src/php/ext/zip/modules  php_zip.lo zip_stream.lo -lzip
cc -shared  .libs/php_zip.o .libs/zip_stream.o  -lzip  -Wl,-O1 -Wl,-soname -Wl,zip.so -o .libs/zip.so
creating zip.la
(cd .libs && rm -f zip.la && ln -s ../zip.la zip.la)
/bin/sh /usr/src/php/ext/zip/libtool --tag=CC --mode=install cp ./zip.la /usr/src/php/ext/zip/modules
cp ./.libs/zip.so /usr/src/php/ext/zip/modules/zip.so
cp ./.libs/zip.lai /usr/src/php/ext/zip/modules/zip.la
PATH="$PATH:/sbin" ldconfig -n /usr/src/php/ext/zip/modules
----------------------------------------------------------------------
Libraries have been installed in:
   /usr/src/php/ext/zip/modules
If you ever happen to want to link against installed libraries
in a given directory, LIBDIR, you must either use libtool, and
specify the full pathname of the library, or use the `-LLIBDIR'
flag during linking and do at least one of the following:
   - add LIBDIR to the `LD_LIBRARY_PATH' environment variable
     during execution
   - add LIBDIR to the `LD_RUN_PATH' environment variable
     during linking
   - use the `-Wl,--rpath -Wl,LIBDIR' linker flag
See any operating system documentation about shared libraries for
more information, such as the ld(1) and ld.so(8) manual pages.
----------------------------------------------------------------------
Build complete.
Don't forget to run 'make test'.
+ strip --strip-all modules/zip.so
Installing shared extensions:     /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
find . -name \*.gcno -o -name \*.gcda | xargs rm -f
find . -name \*.lo -o -name \*.o -o -name \*.dep | xargs rm -f
find . -name \*.la -o -name \*.a | xargs rm -f
find . -name \*.so | xargs rm -f
find . -name .libs -a -type d|xargs rm -rf
rm -f libphp.la      modules/* libs/*
rm -f ext/opcache/jit/zend_jit_x86.c
rm -f ext/opcache/jit/zend_jit_arm64.c
rm -f ext/opcache/minilua
Configuring for:
PHP Api Version:         20220829
Zend Module Api No:      20220829
Zend Extension Api No:   420220829
checking for grep that handles long lines and -e... 
/bin/grep
checking for egrep... 
/bin/grep -E
checking for a sed that does not truncate output... 
/bin/sed
checking for pkg-config... 
/usr/bin/pkg-config
checking pkg-config is at least version 0.9.0... 
yes
checking for cc... 
cc
checking whether the C compiler works... 
yes
checking for C compiler default output file name... a.out
checking for suffix of executables... 
checking whether we are cross compiling... 
no
checking for suffix of object files... 
o
checking whether the compiler supports GNU C... 
yes
checking whether cc accepts -g... 
yes
checking for cc option to enable C11 features... 
none needed
checking how to run the C preprocessor... 
cc -E
checking for egrep -e... 
(cached) 
/bin/grep -E
checking for icc... 
no
checking for suncc... 
no
checking for system library directory... lib
checking if compiler supports -Wl,-rpath,... 
yes
checking build system type... 
x86_64-pc-linux-musl
checking host system type... 
x86_64-pc-linux-musl
checking target system type... 
x86_64-pc-linux-musl
checking for PHP prefix... /usr/local
checking for PHP includes... 
-I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib
checking for PHP extension directory... 
/usr/local/lib/php/extensions/no-debug-non-zts-20220829
checking for PHP installed headers prefix... 
/usr/local/include/php
checking if debug is enabled... 
no
checking if zts is enabled... 
no
checking for gawk... 
no
checking for nawk... 
no
checking for awk... 
awk
checking if awk is broken... 
no
checking whether to enable SOAP support... 
yes, shared
checking for libxml-2.0 >= 2.9.0... 
yes
checking for a sed that does not truncate output... 
/bin/sed
checking for ld used by cc... 
/usr/x86_64-alpine-linux-musl/bin/ld
checking if the linker (/usr/x86_64-alpine-linux-musl/bin/ld) is GNU ld... 
yes
checking for /usr/x86_64-alpine-linux-musl/bin/ld option to reload object files... 
-r
checking for BSD-compatible nm... 
/usr/bin/nm -B
checking whether ln -s works... yes
checking how to recognize dependent libraries... pass_all
checking for stdio.h... 
yes
checking for stdlib.h... 
yes
checking for string.h... 
yes
checking for inttypes.h... 
yes
checking for stdint.h... 
yes
checking for strings.h... 
yes
checking for sys/stat.h... 
yes
checking for sys/types.h... 
yes
checking for unistd.h... 
yes
checking for dlfcn.h... 
yes
checking the maximum length of command line arguments... 
98304
checking command to parse /usr/bin/nm -B output from cc object... 
ok
checking for objdir... 
.libs
checking for ar... 
ar
checking for ranlib... 
ranlib
checking for strip... 
strip
checking if cc supports -fno-rtti -fno-exceptions... 
no
checking for cc option to produce PIC... 
-fPIC
checking if cc PIC flag -fPIC works... 
yes
checking if cc static flag -static works... 
yes
checking if cc supports -c -o file.o... 
yes
checking whether the cc linker (/usr/x86_64-alpine-linux-musl/bin/ld -m elf_x86_64) supports shared libraries... 
yes
checking whether -lc should be explicitly linked in... 
no
checking dynamic linker characteristics... 
GNU/Linux ld.so
checking how to hardcode library paths into programs... 
immediate
checking whether stripping libraries is possible... 
yes
checking if libtool supports shared libraries... yes
checking whether to build shared libraries... 
yes
checking whether to build static libraries... no
creating libtool
appending configuration tag "CXX" to libtool
configure: patching config.h.in
configure: creating ./config.status
config.status: creating config.h
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/soap.c -o soap.lo  -MMD -MF soap.dep -MT soap.lo
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_encoding.c -o php_encoding.lo  -MMD -MF php_encoding.dep -MT php_encoding.lo
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_http.c -o php_http.lo  -MMD -MF php_http.dep -MT php_http.lo
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_packet_soap.c -o php_packet_soap.lo  -MMD -MF php_packet_soap.dep -MT php_packet_soap.lo
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_schema.c -o php_schema.lo  -MMD -MF php_schema.dep -MT php_schema.lo
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_sdl.c -o php_sdl.lo  -MMD -MF php_sdl.dep -MT php_sdl.lo
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=compile cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE   -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_xml.c -o php_xml.lo  -MMD -MF php_xml.dep -MT php_xml.lo
mkdir .libs
mkdir .libs
mkdir: can't create directory '.libs': File exists
 cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/soap.c -MMD -MF soap.dep -MT soap.lo  -fPIC -DPIC -o .libs/soap.o
 cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_encoding.c -MMD -MF php_encoding.dep -MT php_encoding.lo  -fPIC -DPIC -o .libs/php_encoding.o
 cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_http.c -MMD -MF php_http.dep -MT php_http.lo  -fPIC -DPIC -o .libs/php_http.o
 cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_packet_soap.c -MMD -MF php_packet_soap.dep -MT php_packet_soap.lo  -fPIC -DPIC -o .libs/php_packet_soap.o
 cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_schema.c -MMD -MF php_schema.dep -MT php_schema.lo  -fPIC -DPIC -o .libs/php_schema.o
 cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_sdl.c -MMD -MF php_sdl.dep -MT php_sdl.lo  -fPIC -DPIC -o .libs/php_sdl.o
 cc -I. -I/usr/src/php/ext/soap -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2 -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE -DZEND_ENABLE_STATIC_TSRMLS_CACHE=1 -DZEND_COMPILE_DL_EXT=1 -c /usr/src/php/ext/soap/php_xml.c -MMD -MF php_xml.dep -MT php_xml.lo  -fPIC -DPIC -o .libs/php_xml.o
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=link cc -shared -I/usr/src/php/ext/soap/include -I/usr/src/php/ext/soap/main -I/usr/src/php/ext/soap -I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib -I/usr/include/libxml2  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -DHAVE_CONFIG_H  -fstack-protector-strong -fpic -fpie -O2 -D_LARGEFILE_SOURCE -D_FILE_OFFSET_BITS=64 -D_GNU_SOURCE  -Wl,-O1 -pie  -o soap.la -export-dynamic -avoid-version -prefer-pic -module -rpath /usr/src/php/ext/soap/modules  soap.lo php_encoding.lo php_http.lo php_packet_soap.lo php_schema.lo php_sdl.lo php_xml.lo -lxml2
cc -shared  .libs/soap.o .libs/php_encoding.o .libs/php_http.o .libs/php_packet_soap.o .libs/php_schema.o .libs/php_sdl.o .libs/php_xml.o  -lxml2  -Wl,-O1 -Wl,-soname -Wl,soap.so -o .libs/soap.so
creating soap.la
(cd .libs && rm -f soap.la && ln -s ../soap.la soap.la)
/bin/sh /usr/src/php/ext/soap/libtool --tag=CC --mode=install cp ./soap.la /usr/src/php/ext/soap/modules
cp ./.libs/soap.so /usr/src/php/ext/soap/modules/soap.so
cp ./.libs/soap.lai /usr/src/php/ext/soap/modules/soap.la
PATH="$PATH:/sbin" ldconfig -n /usr/src/php/ext/soap/modules
----------------------------------------------------------------------
Libraries have been installed in:
   /usr/src/php/ext/soap/modules
If you ever happen to want to link against installed libraries
in a given directory, LIBDIR, you must either use libtool, and
specify the full pathname of the library, or use the `-LLIBDIR'
flag during linking and do at least one of the following:
   - add LIBDIR to the `LD_LIBRARY_PATH' environment variable
     during execution
   - add LIBDIR to the `LD_RUN_PATH' environment variable
     during linking
   - use the `-Wl,--rpath -Wl,LIBDIR' linker flag
See any operating system documentation about shared libraries for
more information, such as the ld(1) and ld.so(8) manual pages.
----------------------------------------------------------------------
Build complete.
Don't forget to run 'make test'.
+ strip --strip-all modules/soap.so
Installing shared extensions:     /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
find . -name \*.gcno -o -name \*.gcda | xargs rm -f
find . -name \*.lo -o -name \*.o -o -name \*.dep | xargs rm -f
find . -name \*.la -o -name \*.a | xargs rm -f
find . -name \*.so | xargs rm -f
find . -name .libs -a -type d|xargs rm -rf
rm -f libphp.la      modules/* libs/*
rm -f ext/opcache/jit/zend_jit_x86.c
rm -f ext/opcache/jit/zend_jit_arm64.c
rm -f ext/opcache/minilua
Configuring for:
PHP Api Version:         20220829
Zend Module Api No:      20220829
Zend Extension Api No:   420220829
checking for grep that handles long lines and -e... 
/bin/grep
checking for egrep... 
/bin/grep -E
checking for a sed that does not truncate output... 
/bin/sed
checking for pkg-config... 
/usr/bin/pkg-config
checking pkg-config is at least version 0.9.0... 
yes
checking for cc... 
cc
checking whether the C compiler works... 
yes
checking for C compiler default output file name... a.out
checking for suffix of executables... 
checking whether we are cross compiling... 
no
checking for suffix of object files... 
o
checking whether the compiler supports GNU C... 
yes
checking whether cc accepts -g... 
yes
checking for cc option to enable C11 features... 
none needed
checking how to run the C preprocessor... 
cc -E
checking for egrep -e... 
(cached) 
/bin/grep -E
checking for icc... 
no
checking for suncc... 
no
checking for system library directory... 
lib
checking if compiler supports -Wl,-rpath,... 
yes
checking build system type... 
x86_64-pc-linux-musl
checking host system type... 
x86_64-pc-linux-musl
checking target system type... 
x86_64-pc-linux-musl
checking for PHP prefix... /usr/local
checking for PHP includes... 
-I/usr/local/include/php -I/usr/local/include/php/main -I/usr/local/include/php/TSRM -I/usr/local/include/php/Zend -I/usr/local/include/php/ext -I/usr/local/include/php/ext/date/lib
checking for PHP extension directory... 
/usr/local/lib/php/extensions/no-debug-non-zts-20220829
checking for PHP installed headers prefix... /usr/local/include/php
checking if debug is enabled... 
no
checking if zts is enabled... 
no
checking for gawk... 
no
checking for nawk... 
no
checking for awk... 
awk
checking if awk is broken... 
no
checking whether to enable internationalization support... 
yes, shared
checking for icu-uc >= 50.1 icu-io icu-i18n... 
no

configure: error: Package requirements (icu-uc >= 50.1 icu-io icu-i18n) were not met:
Package 'icu-uc' not found
Package 'icu-io' not found
Package 'icu-i18n' not found
Consider adjusting the PKG_CONFIG_PATH environment variable if you
installed software in a non-standard prefix.
Alternatively, you may set the environment variables ICU_CFLAGS
and ICU_LIBS to avoid the need to call pkg-config.
See the pkg-config man page for more details.
