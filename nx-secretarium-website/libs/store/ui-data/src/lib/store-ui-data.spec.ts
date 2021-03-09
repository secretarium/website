import { fetchAPI } from './store-ui-data';

describe('storeUiData', () => {
  it('should work', () => {
    expect(fetchAPI('welcome')).toHaveReturned();
  });
});
