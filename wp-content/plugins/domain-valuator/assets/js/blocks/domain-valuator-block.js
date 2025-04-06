/**
 * Domain Valuator - Gutenberg Block
 * Version: 1.0.0
 */

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl, TextControl, RangeControl } = wp.components;
const { __ } = wp.i18n;

// Register the block
registerBlockType('domain-valuator/main-block', {
    title: __('Domain Valuator', 'domain-valuator'),
    icon: 'chart-line',
    category: 'widgets',
    keywords: [
        __('domain', 'domain-valuator'),
        __('valuation', 'domain-valuator'),
        __('investment', 'domain-valuator')
    ],
    attributes: {
        theme: {
            type: 'string',
            default: 'light'
        },
        results: {
            type: 'string',
            default: domainValuatorSettings.maxResults || '10'
        },
        timeframe: {
            type: 'string',
            default: domainValuatorSettings.defaultTimeframe || '3'
        },
        buttonText: {
            type: 'string',
            default: 'Subscribe Now'
        }
    },
    
    // Edit component
    edit: function(props) {
        const { attributes, setAttributes } = props;
        const { theme, results, timeframe, buttonText } = attributes;
        
        // Inspector controls (sidebar)
        const inspectorControls = (
            <InspectorControls>
                <PanelBody title={__('Domain Valuator Settings', 'domain-valuator')}>
                    <SelectControl
                        label={__('Theme', 'domain-valuator')}
                        value={theme}
                        options={[
                            { label: __('Light', 'domain-valuator'), value: 'light' },
                            { label: __('Dark', 'domain-valuator'), value: 'dark' }
                        ]}
                        onChange={(value) => setAttributes({ theme: value })}
                    />
                    
                    <RangeControl
                        label={__('Number of Results', 'domain-valuator')}
                        value={parseInt(results)}
                        onChange={(value) => setAttributes({ results: value.toString() })}
                        min={1}
                        max={50}
                    />
                    
                    <SelectControl
                        label={__('Default Timeframe', 'domain-valuator')}
                        value={timeframe}
                        options={Array.from({ length: 11 }, (_, i) => i + 2).map(num => ({
                            label: `${num} ${__('months', 'domain-valuator')}`,
                            value: num.toString()
                        }))}
                        onChange={(value) => setAttributes({ timeframe: value })}
                    />
                    
                    <TextControl
                        label={__('Subscribe Button Text', 'domain-valuator')}
                        value={buttonText}
                        onChange={(value) => setAttributes({ buttonText: value })}
                    />
                </PanelBody>
            </InspectorControls>
        );
        
        // Block preview
        return [
            inspectorControls,
            <div className={`domain-valuator-editor-block theme-${theme}`}>
                <div className="block-preview">
                    <h3>{__('Domain Valuator', 'domain-valuator')}</h3>
                    <p>{__('Find profitable domain investments with our AI-powered domain valuation tool', 'domain-valuator')}</p>
                    
                    <div className="block-preview-info">
                        <ul>
                            <li>{__('Theme:', 'domain-valuator')} <strong>{theme}</strong></li>
                            <li>{__('Results:', 'domain-valuator')} <strong>{results}</strong></li>
                            <li>{__('Timeframe:', 'domain-valuator')} <strong>{timeframe} {__('months', 'domain-valuator')}</strong></li>
                            <li>{__('Button Text:', 'domain-valuator')} <strong>{buttonText}</strong></li>
                        </ul>
                    </div>
                    
                    <div className="block-preview-notice">
                        {__('The Domain Valuator will be displayed here on your site.', 'domain-valuator')}
                    </div>
                </div>
            </div>
        ];
    },
    
    // Save component
    save: function() {
        // Dynamic block, rendering is handled by PHP
        return null;
    }
}); 