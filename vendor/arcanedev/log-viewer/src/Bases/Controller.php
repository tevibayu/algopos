<?php namespace Arcanedev\LogViewer\Bases;

use Arcanedev\LogViewer\LogViewer;
use Illuminate\Routing\Controller as IlluminateController;

/**
 * Class     Controller
 *
 * @package  Arcanedev\LogViewer\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Controller extends IlluminateController
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The log viewer instance
     *
     * @var LogViewer
     */
    protected $logViewer;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->logViewer = app('arcanedev.log-viewer');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     *
     * @return \Illuminate\View\View
     */
    public function view($view, $data = [], $mergeData = [])
    {
        access()->settings();
        return view('log-viewer::' . $view, $data, $mergeData);
    }
}
