import { Component, OnInit, ChangeDetectionStrategy } from '@angular/core';

@Component({
    selector: 'm-masters-users-page',
    template: '<router-outlet></router-outlet>',
    changeDetection: ChangeDetectionStrategy.Default
})
export class MatersUsersPageComponent implements OnInit {


    constructor() {}
    ngOnInit() {}
}
