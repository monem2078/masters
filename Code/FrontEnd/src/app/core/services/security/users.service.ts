import {Injectable} from '@angular/core';
import {RequestService} from '../shared/request.service';
import {environment} from '../../../../environments/environment';

@Injectable()
export class UserService {
    constructor(
        private _requestService: RequestService) {
    }

    getCurrentUser() {
        return this._requestService.SendRequest('GET', environment.apiBaseURL + 'authenticate/user', null, null);
    }
    logOut() {
        return this._requestService.SendRequest('POST', environment.apiBaseURL + 'authenticate/invalidate', null, null);
    }

}
