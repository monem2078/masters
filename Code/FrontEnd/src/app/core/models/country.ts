import { BaseModel } from './baseModel';

export class Country extends BaseModel {
    //id is inherited from Resource
    id: number;
    country_name: string;
    Code: string;
    DestinationName: string;
    DestinationCode: string;
}


