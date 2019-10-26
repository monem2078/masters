import { BaseModel } from './baseModel';

export class Package extends BaseModel {
    id: number;
    currency_name: string;
    currency_name_ar: string;
    symbol: string;
    master_id: number;
    category_id: number;
    title: string;
    description: string;
    price: number;
    currency_id: number;
}
