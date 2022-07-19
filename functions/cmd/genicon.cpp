#include <stdio.h>
#include <stdlib.h>
#include <string.h>

typedef struct {
    double r;       // a fraction between 0 and 1
    double g;       // a fraction between 0 and 1
    double b;       // a fraction between 0 and 1
} rgb;

typedef struct {
    double h;       // angle in degrees
    double s;       // a fraction between 0 and 1
    double v;       // a fraction between 0 and 1
} hsv;

static hsv   rgb2hsv(rgb in);
static rgb   hsv2rgb(hsv in);

int main(int argc,char** argv){
    if(argc!=2)return 0;
    printf("P3\n350 350\n255\n");
    char img[10][10];
    char vals[17];
    memcpy(vals,argv[1],16);
    vals[15]='\n';
    unsigned long long val = strtol(vals,NULL,16);
    unsigned long long mask = 1;
    int c=0;
    for(int i=0;i<49;i++){
        if((val & mask)&&(img[(i/7)][(i%7+1)]==0)&&(img[(i/7+1)][(i%7)]==0)){
            img[(i/7+1)][(i%7+1)]=1;
            c++;
        }else{
            img[(i/7+1)][(i%7+1)]=0;
        }
        mask=mask<<1;
    }
    switch(c%4){
    case 0:
        for(int i=0;i<32;i++){
            img[8-(i/7+1)][(i%7+1)]=img[(i/7+1)][(i%7+1)];
        }
        break;
    case 1:
        for(int i=0;i<32;i++){
            img[(i%7+1)][8-(i/7+1)]=img[(i%7+1)][(i/7+1)];
        }
        break;
    case 2:
        for(int i=0;i<64;i++){
            int x=(i/7+1),y=(i%7+1);
            if(x<y){
                img[x][y]=img[y][x];
            }
        }
        break;
    case 3:
        for(int i=0;i<64;i++){
            int x=(i/7+1),y=(i%7+1);
            if(x>y){
                img[x][y]=img[y][x];
            }
        }
        break;
    }
    hsv col = {double(val%360),0.65,0.65};
    rgb color = hsv2rgb(col);
    for(int i=0;i<350;i++){
        for(int j=0;j<350;j++){
            int x= i*7/350,y=j*7/350;
            if(img[x][y]){
                printf("%d %d %d ",int(255*color.r),int(255*color.g),int(255*color.b));
            }else{
                printf("255 255 255 ");
            }
        }
        printf("\n");
    }
}

rgb hsv2rgb(hsv in)
{
    double      hh, p, q, t, ff;
    long        i;
    rgb         out;

    if(in.s <= 0.0) {       // < is bogus, just shuts up warnings
        out.r = in.v;
        out.g = in.v;
        out.b = in.v;
        return out;
    }
    hh = in.h;
    if(hh >= 360.0) hh = 0.0;
    hh /= 60.0;
    i = (long)hh;
    ff = hh - i;
    p = in.v * (1.0 - in.s);
    q = in.v * (1.0 - (in.s * ff));
    t = in.v * (1.0 - (in.s * (1.0 - ff)));

    switch(i) {
    case 0:
        out.r = in.v;
        out.g = t;
        out.b = p;
        break;
    case 1:
        out.r = q;
        out.g = in.v;
        out.b = p;
        break;
    case 2:
        out.r = p;
        out.g = in.v;
        out.b = t;
        break;

    case 3:
        out.r = p;
        out.g = q;
        out.b = in.v;
        break;
    case 4:
        out.r = t;
        out.g = p;
        out.b = in.v;
        break;
    case 5:
    default:
        out.r = in.v;
        out.g = p;
        out.b = q;
        break;
    }
    return out;     
}
