<?php
class RedundancyDetector
{
    /**
     * @var $data
     */
    private $data;

    /**
     * @var $input
     */
    private $input;

    /**
     * @var $message
     */
    private $message;

    /**
     * @var array $total_occurrence
     */
    private $total_occurrence = array();

    /**
     * @var array $word_info
     */
    private $word_info = array();
    /**
     * @param $pattern
     * @param $data
     * @return bool
     */
    private function calculate_occurrence($pattern, $data)
    {
        if($pattern == '' or $data == '')
        {
            $this->set_message('error','invalid input');
            return false;
        }

        $this->data = $data;
        $this->input = $pattern;

        $pattern_length = strlen($data);
        $input_length = strlen($pattern);
        $this->total_occurrence = array();

        for ($i = 0; $i < ($pattern_length-$input_length) + 1; $i++) {
            $j = 0;
            while ($this->data[$i+$j] == $this->input[$j] and  $input_length > $j) {
                $j++;
            }
            if ($j == $input_length){
                array_push($this->total_occurrence,$i);
            }
        }
    }

    /**
     * @param $pattern
     * @param $data
     * @return array
     */
    public function get_total_occurrence($pattern, $data)
    {
        $this->calculate_occurrence($pattern,$data);
        return $this->total_occurrence;
    }

    /**
     * @return bool|int
     */
    public function calculate_words()
    {
        if($this->data == '')
        {
            $this->set_message('error','Subject not set');
            return false;
        }
        $words = 0;
        $lines = explode('\n\r',$this->data);
        foreach ($lines as $line) {
            $words += count(explode(' ',$line));
        }

       $this->word_info['words_count'] =  $words;
    }

    /**
     * @return bool|int
     */
    public function get_total_words()
    {
        if(!isset($this->word_info['words_count']))
            $this->calculate_words();

        return $this->word_info['words_count'];
    }

	/**
	 * @param string $criteria
	 * @return bool|int
	 */
	public function get_word_length($criteria = 'min')
    {
        if($this->data == '')
        {
            $this->set_message('error','Subject not set');
            return false;
        }

        $total_words = preg_replace('/\r\n|\r|\n/',' ',$this->data);
        $count = 0;
        if($criteria == 'max'){
            $count = strlen(array_reduce(explode(' ',$total_words),
                function ($k,$v) { return (strlen($k) > strlen($v)) ? $k : $v; }
            ));
        }

        if($criteria == 'min')
        {
            $count = strlen(array_reduce(explode(' ',$total_words),
                function ($k,$v) {
                    if (!$k) return PHP_INT_MAX;
                    return (strlen($k) < strlen($v)) ? $k : $v;
                }
            ));
        }
        return $count;
    }

	/**
	 * @return bool|float
	 */
	public function get_average_word_length()
    {
		if($this->data == '')
		{
			$this->set_message('error','Subject not set');
			return false;
		}

		$total_words = preg_replace('/\r\n|\r|\n/',' ',$this->data);
		$words_array = explode(' ',$total_words);
		$word_lengths = array();

		foreach ($words_array as $word) {
			array_push($word_lengths,strlen($word));
		}
		return (float)array_sum($word_lengths)/ count($word_lengths);

	}

	/**
	 * @return bool|int
	 */
	public function get_newline_char_count()
    {
		if($this->data == '')
		{
			$this->set_message('error','Subject not set');
			return false;
		}
		$this->word_info['return_caret_count'] = 0;
        return  preg_match_all('/\r\n|\r|\n/',$this->data,$this->word_info['return_caret_count']);
    }

	/**
	 * @return int
	 */
	public function get_longest_recurrence()
	{
		$reccurrance = $this->calculate_recurrence();
		$longest = 0;
		foreach ($reccurrance as $rec) {
			if($longest < strlen($rec))
				$longest = strlen($rec);
		}

		return $longest;
	}

	/**
	 * @return string
	 */
	public function get_longest_recurrence_chunk()
    {
		$reccurrance = $this->calculate_recurrence();
		$rec = '';
		foreach ($reccurrance as $reccur) {
			if(strlen($rec) < strlen($reccur))
				$rec = $reccur;
		}

		return $rec;
	}

	/**
	 * @return array
	 */
	public function calculate_recurrence()
	{
		$data = preg_replace('/\r\n|\r|\n/',' ',$this->data);
		$positions = $this->get_total_occurrence($this->input,$data);
		$prev = '';
		$reccurrance = array();
		for($i=0; $i<sizeof($positions); $i++)
		{
			if($i==0)
				$prev = $positions[$i];
			else{
				array_push($reccurrance,substr($data,$prev,$positions[$i]+(strlen($this->input)-1)));
				$prev = $positions[$i];
			}
		}
		return $reccurrance;
	}

	/**
	 * @return mixed
	 */
	public function get_message()
    {
        return $this->message;
    }

	/**
	 * @param $message
	 */
	public function set_message($message)
    {
        $this->message = $message;
    }
}