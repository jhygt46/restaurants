class Recursive {
    constructor () {
        this.x = 0;
    }
    toString () {
        this.x ++;
        console.log(this.x);
        if(this.x < 7){
            this.toString();
        }
    }
}