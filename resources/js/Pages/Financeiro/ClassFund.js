export default class Fund {
  constructor(name, balance) {
    this.name = name;
    this.balance = balance;
    this.description = '';
    this.initial_value = 0;
    this.current_value = 0;
    this.type = '';
    this.status = '';
    this.created_at = '';
    this.updated_at = '';
  }
};