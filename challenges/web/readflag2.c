#include <stdio.h>
#include <string.h>

int main(int argc, char **argv) {
    seteuid(0);
    setegid(0);
    setuid(0);
    setgid(0);

    if (argc != 2) {
        return 0;
    }

    if( strstr(argv[1], "/../") == NULL) {
        return 0;
    }

    char flag[256] = {0};
    FILE* fp = fopen("{FLAG}", "r");
    if (!fp) {
        perror("fopen");
        return 1;
    }
    if (fread(flag, 1, 256, fp) < 0) {
        perror("fread");
        return 1;
    }
    puts("Congrats! You found the unrestricted file upload vulnerability.");
    puts("Now, can you get shell?");
    puts(flag);
    fclose(fp);
    return 0;
}
