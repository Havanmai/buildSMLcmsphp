var dataarray;
var array=[];
var count =0;
(function (global) {
    "use strict";
    
    class SerialScaleController {
        constructor() {
            this.encoder = new TextEncoder();
            this.decoder = new TextDecoder();
        }
        async init() {
            if ('serial' in navigator) {
                try {
                    const port = await navigator.serial.requestPort();
                    await port.open({ baudRate: 9600 });
                    this.reader = port.readable.getReader();
                    let signals = await port.getSignals();
                    console.log(signals);
                }
                catch (err) {
                    console.error('There was an error opening the serial port:', err);
                }
            }
            else {
                console.error('Web serial doesn\'t seem to be enabled in your browser. Try enabling it by visiting:');
                console.error('chrome://flags/#enable-experimental-web-platform-features');
                console.error('opera://flags/#enable-experimental-web-platform-features');
                console.error('edge://flags/#enable-experimental-web-platform-features');
            }
        }
        async read() {
            try {
                let { value, done } = await this.reader.read()
          
                if(done){
                 this.reader.releaseLock();
                }
                if(value){
              
                    array.push(this.decoder.decode(value));
   
                    
                    let lastArr= array.length-1;
                    
                    if((array[lastArr]=='   0.00\n\r\n\r')||(array[lastArr]=='   0.00\n')){
                        if(lastArr>0){
                            this.dataarray=array[lastArr-1];
                            return this.dataarray.slice(this.dataarray.indexOf("W")+4,this.dataarray.indexOf("W")+11);
                        }else{
                            return 'Vui lòng đặt lại cân';
                        }
                    }else{
                        this.dataarray=array[lastArr];
                        return this.dataarray.slice(this.dataarray.indexOf("W")+4,this.dataarray.indexOf("W")+11);
                    }
                }
                
            }
            catch (err) {
                const errorMessage = `error reading data: ${err}`;
                console.error(errorMessage);
                return errorMessage;
            }
        }
    }
    var serialScaleController;
    global.WeightSDK = {
        init: function () {
            console.log("init");
            serialScaleController = new SerialScaleController();
            serialScaleController.init();                      
        },
        readData: function() {
            if (serialScaleController == undefined) console.log("serialScaleController is undefined")
            return serialScaleController.read()
        }
    };

})(this);