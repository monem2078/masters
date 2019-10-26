import { Injectable } from '@angular/core';
import { RequestService } from '../shared/request.service';
import { environment } from '../../../../environments/environment';
import { BaseModel } from '../../models/baseModel';
import { Observable, of } from 'rxjs';

@Injectable()
export class CountryService {
	constructor(
		private _requestService: RequestService) {
	}
	cities<T extends BaseModel>(id: number) {
		return this._requestService.SendRequest
			('GET', environment.apiBaseURL + 'countries/' + id + '/cities', null, null);
	}
}
