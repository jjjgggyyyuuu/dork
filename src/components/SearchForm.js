import React, { useState } from 'react';

const SearchForm = ({ onSearch }) => {
  const defaultTimeFrame = parseInt(process.env.NEXT_PUBLIC_DEFAULT_TIMEFRAME || 3);
  const [maxPrice, setMaxPrice] = useState(1000);
  const [timeFrame, setTimeFrame] = useState(defaultTimeFrame);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    try {
      await onSearch({ maxPrice, timeFrame });
    } catch (error) {
      console.error('Search error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="card">
      <h2 className="mb-4 text-xl font-semibold text-dark">Find Profitable Domains</h2>
      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <label htmlFor="maxPrice" className="block mb-2 text-sm font-medium text-gray-700">
            Maximum Price ($)
          </label>
          <input
            type="number"
            id="maxPrice"
            className="input-field"
            value={maxPrice}
            onChange={(e) => setMaxPrice(Number(e.target.value))}
            min="10"
            max="100000"
            required
          />
        </div>
        <div className="mb-6">
          <label htmlFor="timeFrame" className="block mb-2 text-sm font-medium text-gray-700">
            Profitability Timeframe (months)
          </label>
          <select
            id="timeFrame"
            className="input-field"
            value={timeFrame}
            onChange={(e) => setTimeFrame(Number(e.target.value))}
            required
          >
            <option value="2">2 months</option>
            <option value="3">3 months</option>
            <option value="4">4 months</option>
            <option value="6">6 months</option>
            <option value="12">12 months</option>
          </select>
        </div>
        <button 
          type="submit" 
          className={`btn btn-primary w-full ${loading ? 'opacity-75 cursor-not-allowed' : ''}`}
          disabled={loading}
        >
          {loading ? 'Analyzing Domains...' : 'Find Profitable Domains'}
        </button>
      </form>
    </div>
  );
};

export default SearchForm; 