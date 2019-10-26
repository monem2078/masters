import { Component, OnInit, ChangeDetectionStrategy } from '@angular/core';


// Material
@Component({
    selector: 'm-add-category',
    templateUrl: './add-category.component.html',
    changeDetection: ChangeDetectionStrategy.Default
})

export class AddCategoryComponent implements OnInit {
    imageChangedEvent: any = '';
    croppedImage: any = '';
    
    fileChangeEvent(event: any): void {
        this.imageChangedEvent = event;
    }
    // imageCropped(event: ImageCroppedEvent) {
    //     this.croppedImage = event.base64;
    // }
    imageLoaded() {
        // show cropper
    }
    cropperReady() {
        // cropper ready
    }
    loadImageFailed() {
        // show message
    }
   
	constructor() { }

	ngOnInit() {
     
    }



  }
